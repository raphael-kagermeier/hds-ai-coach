<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

/**
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property string $content
 * @property int $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $images
 * @property-read \App\Models\Course|null $course
 * @property-read array $base64_images
 * @property-read string $formatted_content
 *
 * @method static \Database\Factories\LessonFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Lesson whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Lesson extends Model implements Sortable
{
    use HasFactory, SortableTrait;

    protected $fillable = ['name', 'course_id', 'content', 'order_column', 'images'];

    protected $casts = [
        'images' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
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

    public function getFormattedContentAttribute(): string
    {
        return "**{$this->name}**\n{$this->content}";
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => Str::markdown($value ?? ''),
            set: fn (string $value) => Str::of($value)->markdown()->toString(),
        );
    }
}
