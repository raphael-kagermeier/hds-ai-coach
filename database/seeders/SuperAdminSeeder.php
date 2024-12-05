<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Raphael Kagermeier',
            'email' => 'r.kagermeier@performromance.com',
            'password' => Hash::make('password')
        ])->assignRole('Super Admin');

        User::factory()->create([
            'name' => 'Kathi Elli',
            'email' => 'hello@wednesday-concepts.com',
            'password' => Hash::make('password')
        ])->assignRole('Super Admin');
    }
}
