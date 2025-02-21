<?php

namespace SpeeechTexter\Resources\Client;

use App\Http\Resources\Core\Client\FileResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpeeechTexterResource extends JsonResource
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
            "text"      => $this->result['asr_text'] ?? null,
            "createdAt" => $this->created_at,
        ];
    }

}
