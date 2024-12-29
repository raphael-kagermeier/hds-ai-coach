<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Models\Lesson;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
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
        ];
    }

    public function withLessons(int $count = 1): static
    {
        return $this->afterCreating(function (Course $course) use ($count) {
            Lesson::factory()
                ->count($count)
                ->for($course)
                ->create();
        });
    }
}
