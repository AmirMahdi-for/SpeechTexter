<?php

namespace SpeeechTexter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeeechTexter extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'file_id', 'file_url', 'file', 'result', 'response_status_code'];

    protected $casts = [
        'result' => 'json',
    ];

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
