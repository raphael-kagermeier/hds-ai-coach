<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Raphael Kagermeier',
                'email' => 'r.kagermeier@performromance.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kathi Elli',
                'email' => 'hello@wednesday-concepts.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($data as $d) {
            $user = User::firstOrCreate(
                ['email' => $d['email']],
                [
                    ...$d,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            
            $user->assignRole(RolesEnum::SuperAdmin);
        }
    }
}
