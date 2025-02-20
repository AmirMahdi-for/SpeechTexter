<?php

namespace App\Services\Ussistant\Repositories;

use Illuminate\Support\Facades\Http;
use App\Helper\ConvertsCamelCaseToSnakeCase;
use App\Services\Ussistant\Models\Ussistant;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use App\Services\Ussistant\Repositories\Interfaces\UssistantRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UssistantRepository implements UssistantRepositoryInterface
{
   use ConvertsCamelCaseToSnakeCase;

   public function speechToText(int $userId, $fileId, array $parameters) 
   {
      try {
         $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-Key' => env('USSISTANT_X_API_KEY'),
         ])->attach(
            'file',
            file_get_contents($parameters['file']->getPathname()),
            $parameters['file']->getClientOriginalName(),
         )->post(env('USSISTANT_VOICE_API'));

         $statusCode = $response->status();
         $responseBody = json_decode($response->body(), true);

         $ussistant = Ussistant::create([
            "file_id" => $fileId,
            "result" => $responseBody,
            "response_status_code" => $statusCode,
         ]);

         return $ussistant;
      }
      catch (ConnectionException $e) {
         Log::info($e);
         return $this->handleError($fileId, 500, "Connection Error");
      } catch (RequestException $e) {
         Log::info($e);
         return $this->handleError($fileId, $e->response->status(), "Request Error");
      } catch (\Exception $e) {
         Log::info($e);
         return $this->handleError($fileId, 400, $e->getMessage());
      }
   }

   private function handleError($fileId, $statusCode, $errorMessage)
   {
      Ussistant::create([
         "file_id" => $fileId,
         "text_result" => null,
         "response_status_code" => $statusCode,
      ]);
   }
}