<?php

namespace App\SpeeechTexter\Repositories;

use Illuminate\Support\Facades\Http;
use App\Helper\ConvertsCamelCaseToSnakeCase;
use App\Repositories\Interfaces\SpeeechTexterRepositoryInterface;
use App\Services\SpeeechTexter\Models\SpeeechTexter;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class SpeeechTexterRepository implements SpeeechTexterRepositoryInterface
{

   public function speechToText(int $userId, $fileId, array $parameters) 
   {
      try {
         $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-Key' => env('SpeeechTexter_X_API_KEY'),
         ])->attach(
            'file',
            file_get_contents($parameters['file']->getPathname()),
            $parameters['file']->getClientOriginalName(),
         )->post(env('SpeeechTexter_VOICE_API'));

         $statusCode = $response->status();
         $responseBody = json_decode($response->body(), true);

         $SpeeechTexter = SpeeechTexter::create([
            "file_id" => $fileId,
            "result" => $responseBody,
            "response_status_code" => $statusCode,
         ]);

         return $SpeeechTexter;
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
      SpeeechTexter::create([
         "file_id" => $fileId,
         "text_result" => null,
         "response_status_code" => $statusCode,
      ]);
   }
}