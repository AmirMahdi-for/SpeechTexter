<?php


return [
    'api_key' => env('SPEECH_TEXTER_X_API_KEY'),
    'voice_api' => env('SPEECH_TEXTER_VOICE_API'),
    'user_model' => env('SPEECH_TEXTER_USER_MODEL', 'App\Models\User'),
    'file_model' => env('SPEECH_TEXTER_FILE_MODEL', 'App\Models\File'),
    'prefix' => 'speech-texter',
];