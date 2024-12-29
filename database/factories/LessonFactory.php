<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(nbWords: 3),
            'course_id' => Course::factory(),
            'content' => fake()->paragraph(nbSentences: 3),
        ];
    }
}

