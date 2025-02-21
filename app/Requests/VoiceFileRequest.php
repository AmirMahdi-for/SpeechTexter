<?php

namespace SpeeechTexter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_url' => ['nullable', 'url'],
            'file' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
