<?php

namespace Tests;

use App\Enums\Auth\Roles;
use App\Models\Auth\User;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionRoleSeeder::class);
    }

    protected function makeUser(Roles $role = Roles::USER): User
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($role->value);

        return $user;
    }

    protected function makeAdmin(): User
    {
        return $this->makeUser(Roles::ADMINISTRATOR);
    }
}
