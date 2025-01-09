<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Support\Facades\Storage;

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
            return Storage::disk('public')->path($image);
        }, $this->images);
    }
}
