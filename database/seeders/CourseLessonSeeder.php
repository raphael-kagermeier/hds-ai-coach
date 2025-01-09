<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Course::factory(5)->withLessons(10)->create();
    }
}
