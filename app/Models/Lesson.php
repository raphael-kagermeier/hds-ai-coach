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

    public function getImagePathsAttribute(): array
    {
        return array_map(function (string $image) {
            return Storage::path($image);
        }, $this->images);
    }

    public function getFormattedContentAttribute(): string
    {
        return "**{$this->name}**\n{$this->content}";
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => Str::markdown($value ?? ''),
            set: fn(string $value) => Str::of($value ?? '')->markdown()->toString() ?? null,
        );
    }
}
