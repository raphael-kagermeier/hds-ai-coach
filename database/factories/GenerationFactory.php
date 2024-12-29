<?php

namespace Database\Factories;

use App\Models\Generation;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Generation>
 */
class GenerationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Generation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'input_text' => fake()->paragraphs(3, true),
            'lesson_id' => Lesson::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'failed']),
            'images' => fake()->boolean(30) ? [fake()->imageUrl()] : null,
            'generated_text' => fake()->boolean(80) ? fake()->paragraphs(5, true) : null,
            'final_text' => fake()->boolean(50) ? fake()->paragraphs(4, true) : null,
        ];
    }

    /**
     * Indicate that the generation was successful.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'generated_text' => fake()->paragraphs(5, true),
        ]);
    }

    /**
     * Indicate that the generation failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'generated_text' => null,
            'final_text' => null,
        ]);
    }

    /**
     * Indicate that the generation has images.
     */
    public function withImages(int $count = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'images' => array_map(
                fn () => fake()->imageUrl(),
                range(1, $count)
            ),
        ]);
    }
}
