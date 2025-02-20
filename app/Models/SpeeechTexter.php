<?php

namespace SpeeechTexter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpeeechTexter extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'result' => 'json',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
