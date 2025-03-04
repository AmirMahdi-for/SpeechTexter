<?php

namespace SpeechTexter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SpeechTexter\Models\SpeechTexter;

class StoreSpeechResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $userId;
    protected int $fileId;
    protected array $responseBody;
    protected int $statusCode;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $fileId, array $responseBody, int $statusCode)
    {
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->responseBody = $responseBody;
        $this->statusCode = $statusCode;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        SpeechTexter::create([
            "file_id" => $this->fileId,
            "result" => $this->responseBody,
            "response_status_code" => $this->statusCode,
        ]);
    }
}
