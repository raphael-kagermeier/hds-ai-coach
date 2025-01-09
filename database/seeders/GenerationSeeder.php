<?php

namespace Database\Seeders;

use App\Models\Generation;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class GenerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing users and lessons to attach generations to
        $users = User::all();
        $lessons = Lesson::all();

        if ($users->isEmpty() || $lessons->isEmpty()) {
            $this->command->warn('Please seed users and lessons first!');

            return;
        }

        // Create 50 random generations
        Generation::withoutEvents(function () use ($users, $lessons) {
            Generation::factory()
                ->count(50)
                ->sequence(fn ($sequence) => [
                    'user_id' => $users->random()->id,
                    'lesson_id' => $lessons->random()->id,
                ])
                ->create();
        });

        // Create 10 completed generations with images
        Generation::withoutEvents(function () use ($users, $lessons) {
            Generation::factory()
                ->count(10)
                ->completed()
                ->withImages(2)
                ->sequence(fn ($sequence) => [
                    'user_id' => $users->random()->id,
                    'lesson_id' => $lessons->random()->id,
                ])
                ->create();
        });
    }
}
