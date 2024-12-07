<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->withRole(RolesEnum::SuperAdmin)
            ->create([
                'name' => 'Raphael Kagermeier',
                'email' => 'r.kagermeier@performromance.com',
                'password' => Hash::make('password'),
            ]);

        User::factory()
            ->withRole(RolesEnum::SuperAdmin)
            ->create([
                'name' => 'Kathi Elli',
                'email' => 'hello@wednesday-concepts.com',
                'password' => Hash::make('password'),
            ]);
    }
}
