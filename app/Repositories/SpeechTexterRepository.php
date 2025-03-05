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
        $apiKey = config('speech-texter.api_key');
        $apiUrl = config('speech-texter.voice_api');

        $parameters = (new VoiceFileRequest())->validate($parameters);

        try {
            $fileData = $this->getFileData($parameters);
            $fileName = $this->getFileName($parameters);
            
            $response = $this->sendToSpeechApi($apiKey, $apiUrl, $fileData, $fileName);
            
            $this->cleanupCache($parameters, $fileName);
            
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
            
            $this->cleanupCache($parameters, $fileName ?? null);
            
            return response()->json([
                'message' => 'An error occurred during the speech-to-text process.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function getFileData(array $parameters): string 
    {
        if (isset($parameters['file_url'])) {
            $fileContent = Http::get($parameters['file_url'])->body();
            $fileName = 'temp_' . Str::random(10);
            
            Cache::put($fileName, base64_encode($fileContent), 3600);
            
            return base64_decode(Cache::get($fileName));
        }
        
        return file_get_contents($parameters['file']->getPathname());
    }

    private function getFileName(array $parameters): string
    {
        if (isset($parameters['file_url'])) {
            return 'temp_' . Str::random(10);
        }
        
        return $parameters['file']->getClientOriginalName();
    }

    private function sendToSpeechApi(string $apiKey, string $apiUrl, string $fileData, string $fileName)
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-Key' => $apiKey,
        ])->attach(
            'file', $fileData, $fileName
        )->post($apiUrl);
    }

    private function cleanupCache(array $parameters, ?string $fileName): void
    {
        if (isset($parameters['file_url']) && $fileName) {
            Cache::forget($fileName);
        }
    }
}
