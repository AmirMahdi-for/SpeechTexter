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

            dispatch(new StoreSpeechResultJob($fileId, $responseBody, $statusCode));

            return response()->json([
                'message' => 'Processing started. The result will be available soon.',
                'status' => $statusCode,
                'data' => $responseBody
            ], 202);

        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
