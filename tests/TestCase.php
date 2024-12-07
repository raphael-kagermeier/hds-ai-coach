<?php

namespace Tests;

use App\Enums\RolesEnum;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    protected User $superAdminUser;

    protected User $basicUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        $this->superAdminUser = User::factory()->withRole(RolesEnum::SuperAdmin)->create([
            'password' => Hash::make('password'),
        ]);

        $this->basicUser = User::factory()->withRole(RolesEnum::Basic)->create([
            'password' => Hash::make('password'),
        ]);

        $this->withoutVite();
    }
}
