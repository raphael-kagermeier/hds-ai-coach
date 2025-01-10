<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $casts = [
        'multiple_images' => 'array',
        'multiple_images_private' => 'array',
    ];

    protected $fillable = [
        'single_image',
        'multiple_images',
        'single_image_private',
        'multiple_images_private',
    ];
}
