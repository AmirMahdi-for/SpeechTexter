<?php

namespace SpeeechTexter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpeeechTexter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'speeech_texter';

    protected $casts = [
        'result' => 'json',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
