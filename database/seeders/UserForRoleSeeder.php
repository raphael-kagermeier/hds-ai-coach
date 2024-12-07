<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Role::all() as $role) {
            User::factory()->create([
                'name' => $role->name.' User',
                'password' => Hash::make('password'),
            ])->assignRole($role->name);
        }
    }
}
