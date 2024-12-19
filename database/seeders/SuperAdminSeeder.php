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
                'email' => 'r.kagermeier@performromance1.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kathi Elli',
                'email' => 'hello@wednesday-concepts1.com',
                'password' => Hash::make('password'),
            ]
        ];

        foreach ($data as $d) {
            User::create([
                ...$d,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ])->assignRole(RolesEnum::SuperAdmin);
        }


//        User::factory()
//            ->withRole(RolesEnum::SuperAdmin)
//            ->create([
//                'name' => 'Raphael Kagermeier',
//                'email' => 'r.kagermeier@performromance.com',
//                'password' => Hash::make('password'),
//            ]);
//
//        User::factory()
//            ->withRole(RolesEnum::SuperAdmin)
//            ->create([
//                'name' => 'Kathi Elli',
//                'email' => 'hello@wednesday-concepts.com',
//                'password' => Hash::make('password'),
//            ]);
    }
}
