<?php

namespace SpeechTexter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeechTexter extends Model
{
    use HasFactory;

    protected $table = 'speech_texter';

    protected $fillable = ['user_id', 'file_id', 'file_url', 'file_data', 'result', 'response_status_code'];

    protected $casts = [
        'result' => 'json',
    ];

    public function file(): BelongsTo {
        $fileModel = config('speech-texter.file_model');
        return $this->belongsTo($fileModel);
    }
    
    public function user(): BelongsTo {
        $userModel = config('speech-texter.user_model');
        return $this->belongsTo($userModel);
    }
}
