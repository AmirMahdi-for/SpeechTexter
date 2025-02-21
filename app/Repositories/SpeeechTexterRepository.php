<?php

namespace SpeeechTexter\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SpeeechTexter\Http\Requests\VoiceFileRequest;
use SpeeechTexter\Repositories\Interfaces\SpeeechTexterRepositoryInterface;

class SpeeechTexterRepository implements SpeeechTexterRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters)
    {
        $apiKey = config('speeech_texter.api_key');
        $apiUrl = config('speeech_texter.voice_api');

        $parameters = VoiceFileRequest::validate($parameters);

        try {
            if (isset($parameters['file_url'])) {
                // Fetch file content from the URL and store it in cache (RAM)
                $fileContent = Http::get($parameters['file_url'])->body();
                $fileName = 'temp_' . Str::random(10); // File name doesn't need extension if we aren't concerned with the file type

                // Store file content in cache
                Cache::put($fileName, $fileContent, 3600); // The third parameter is the TTL (Time To Live)

                // You can use the cache file data directly in the request
                $fileData = Cache::get($fileName); // Fetch the file content from cache

            } else {
                // Handle the case where the file is uploaded via the form
                $fileData = file_get_contents($parameters['file']->getPathname());
                $fileName = $parameters['file']->getClientOriginalName();
            }

            // Send the file to the Speech-to-Text API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $apiKey,
            ])->attach(
                'file', $fileData, $fileName // Send the file content directly from cache or input
            )->post($apiUrl);

            // After processing, we can delete the cache if it was used
            if (isset($parameters['file_url'])) {
                Cache::forget($fileName); // Clean up cache after processing
            }

            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);

            dispatch(new StoreSpeechResultJob($fileId, $responseBody, $statusCode));

            return response()->json([
                'message' => 'Processing started. The result will be available soon.',
                'status' => $statusCode,
                'data' => $responseBody
            ], 202);

        } catch (\Exception $e) {
            Log::error("Error processing speech-to-text: " . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => 'An error occurred during the speech-to-text process.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
