<?php

namespace SpeeechTexter\Repositories;

use Illuminate\Support\Facades\Http;
use SpeeechTexter\Repositories\Interfaces\SpeeechTexterRepositoryInterface;
use SpeeechTexter\Models\SpeeechTexter;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class SpeeechTexterRepository implements SpeeechTexterRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters) 
    {
        $apiKey = config('speeech_texter.api_key');
        $apiUrl = config('speeech_texter.voice_api');
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $apiKey,
            ])->attach(
                'file',
                file_get_contents($parameters['file']->getPathname()),
                $parameters['file']->getClientOriginalName()
            )->post($apiUrl);

            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);

            return SpeeechTexter::create([
                "file_id" => $fileId,
                "result" => $responseBody,
                "response_status_code" => $statusCode,
            ]);
        } catch (ConnectionException $e) {
            Log::error("Connection Error: " . $e->getMessage());
            return $this->handleError($fileId, 500, "Connection Error");
        } catch (RequestException $e) {
            Log::error("Request Error: " . $e->getMessage());
            return $this->handleError($fileId, $e->response->status(), "Request Error");
        } catch (\Exception $e) {
            Log::error("General Error: " . $e->getMessage());
            return $this->handleError($fileId, 400, $e->getMessage());
        }
    }

    private function handleError(int $fileId, int $statusCode, string $errorMessage)
    {
        return SpeeechTexter::create([
            "file_id" => $fileId,
            "result" => null,
            "response_status_code" => $statusCode,
        ]);
    }
}
