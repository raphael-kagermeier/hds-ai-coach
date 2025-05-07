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
/**
 * @property int $id
 * @property string|null $input_text
 * @property int $lesson_id
 * @property int $user_id
 * @property string $status
 * @property array<array-key, mixed>|null $images
 * @property string|null $generated_text
 * @property string $final_text The final text if the user edits the generated text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property string|null $image_review
 * @property-read array $base64_images
 * @property-read bool $has_final_text
 * @property-read bool $has_generated_text
 * @property-read bool $has_images
 * @property-read \App\Models\Lesson|null $lesson
 * @property-read \App\Models\User|null $user
 *
 * @method static \Database\Factories\GenerationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereFinalText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereGeneratedText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereImageReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereInputText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Generation whereUserId($value)
 *
 * @mixin \Eloquent
 */
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

    public function getBase64ImagesAttribute(): array
    {
        return array_map(function (string $image) {
            if (! Storage::disk('public')->exists($image)) {
                return '';
            }

            return 'data:image/'.
                Str::afterLast($image, '.').
                ';base64,'.
                base64_encode(Storage::disk('public')->get($image));
        }, $this->images);
    }

    protected function finalText(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => Str::markdown($value ?? ''),
            set: fn (string $value) => Str::of($value)->markdown()->toString(),
        );
    }
}
