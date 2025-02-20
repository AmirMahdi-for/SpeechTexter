<?php

namespace App\Services\Ussistant\Resources\Client;

use App\Http\Resources\Core\Client\FileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UssistantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"        => $this->id,
            "file"      => new FileResource($this->file),
            "text"      => $this->result['asr_text'] ?? null,
            "createdAt" => $this->created_at,
        ];
    }

}
