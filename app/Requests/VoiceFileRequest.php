<?php

namespace SpeechTexter\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_url' => ['nullable', 'url', 'regex:/\.(mp3|wav|webm|ogg|aac|flac|m4a|amr)$/i', ],
            'file'     => ['nullable', 'file', 'max:10240', 'mimes:mp3,wav,webm,ogg,aac,flac,m4a,amr'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
