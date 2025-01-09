<?php

namespace App\Models;

use App\Observers\GenerationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[ObservedBy(GenerationObserver::class)]
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
        'image_review',
        'name',
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

    public function getImagePathsAttribute(): array
    {
        return array_map(function (string $image) {
            return Storage::disk('public')->path($image);
        }, $this->images);
    }

    protected function finalText(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => Str::markdown($value ?? ''),
            set: fn(string $value) => Str::of($value)->markdown()->toString() ?? null,
        );
    }
}
