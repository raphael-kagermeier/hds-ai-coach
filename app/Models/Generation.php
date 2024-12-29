<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Generation extends Model
{
    /** @use HasFactory<\Database\Factories\GenerationFactory> */
    use HasFactory;

    protected $fillable = [
        'input_text',
        'lesson_id',
        'user_id',
        'status',
        'images',
        'generated_text',
        'final_text',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHasGeneratedTextAttribute(): bool
    {
        return ! is_null($this->generated_text);
    }

    public function getHasFinalTextAttribute(): bool
    {
        return ! is_null($this->final_text);
    }

    public function getHasImagesAttribute(): bool
    {
        return ! empty($this->images);
    }
}
