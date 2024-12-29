<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\EloquentSortable\Sortable;


class Lesson extends Model implements Sortable
{
    /** @use HasFactory<\Database\Factories\LessonsFactory> */
    use HasFactory, SortableTrait;

    protected $fillable = ['name', 'course_id', 'content', 'order_column'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
