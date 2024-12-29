<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CourseLessonSeeder::class,
            app()->environment('local', 'testing', 'staging')
                ? UserForRoleSeeder::class
                : SuperAdminSeeder::class,
        ]);
    }
}
