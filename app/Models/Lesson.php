<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    /** @use HasFactory<\Database\Factories\LessonsFactory> */
    use HasFactory;

    protected $fillable = ['name', 'course_id', 'content'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
