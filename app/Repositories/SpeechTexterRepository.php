<?php

namespace SpeechTexter\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SpeechTexter\Jobs\StoreSpeechResultJob;
use SpeechTexter\Repositories\Interfaces\SpeechTexterRepositoryInterface;
use SpeechTexter\Requests\VoiceFileRequest;

class SpeechTexterRepository implements SpeechTexterRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters)
    {
        $apiKey = config('speech_texter.api_key');
        $apiUrl = config('speech_texter.voice_api');

        $parameters = VoiceFileRequest::validate($parameters);

        try {
            if (isset($parameters['file_url'])) {
                // Fetch file content from the URL and store it in cache
                $fileContent = Http::get($parameters['file_url'])->body();
                $fileName = 'temp_' . Str::random(10); // No need for extension
        
                // Store file content in cache as binary
                Cache::put($fileName, base64_encode($fileContent), 3600); // Store as base64 to preserve integrity
        
                // Fetch file content from cache and decode it
                $fileData = base64_decode(Cache::get($fileName));
            } else {
                // Handle uploaded file
                $fileData = file_get_contents($parameters['file']->getPathname());
                $fileName = $parameters['file']->getClientOriginalName();
            }
        
            // Send the file to the Speech-to-Text API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-Key' => $apiKey,
            ])->attach(
                'file', $fileData, $fileName
            )->post($apiUrl);
        
            // Cleanup cache after processing
            if (isset($parameters['file_url'])) {
                Cache::forget($fileName);
            }
        
            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);
        
            dispatch(new StoreSpeechResultJob($userId, $fileId, $responseBody, $statusCode));
        
            return response()->json([
                'message' => 'Processing started. The result will be available soon.',
                'status' => $statusCode,
                'data' => $responseBody
            ], 202);
        
        } catch (\Exception $e) {
            Log::error("Error processing speech-to-text: " . $e->getMessage(), ['exception' => $e]);
        
            // Ensure cache cleanup on failure
            if (isset($parameters['file_url'])) {
                Cache::forget($fileName);
            }
        
            return response()->json([
                'message' => 'An error occurred during the speech-to-text process.',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
