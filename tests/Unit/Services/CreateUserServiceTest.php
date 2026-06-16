<?php

namespace Tests\Unit\Services;

use App\Enums\Auth\Permissions;
use App\Enums\Auth\Roles;
use App\Models\Auth\User;
use App\Services\Auth\CreateUserService;
use Tests\TestCase;

class CreateUserServiceTest extends TestCase
{
    private CreateUserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CreateUserService;
    }

    public function test_creates_user_with_role_and_permissions(): void
    {
        $payload = [
            'data' => [
                User::FIELD_NAME => 'Test User',
                User::FIELD_EMAIL => 'test@example.com',
                User::FIELD_PASSWORD => 'P@ssword123!',
                'password_confirmation' => 'P@ssword123!',
            ],
            'role' => Roles::USER->value,
            'permissions' => [],
        ];

        $user = $this->service->handle($payload);

        $this->assertDatabaseHas('users', [User::FIELD_EMAIL => 'test@example.com']);
        $this->assertTrue($user->hasRole(Roles::USER->value));
    }

    public function test_password_is_hashed_on_creation(): void
    {
        $payload = [
            'data' => [
                User::FIELD_NAME => 'Hash Test',
                User::FIELD_EMAIL => 'hash@example.com',
                User::FIELD_PASSWORD => 'P@ssword123!',
                'password_confirmation' => 'P@ssword123!',
            ],
            'role' => Roles::USER->value,
            'permissions' => [],
        ];

        $user = $this->service->handle($payload);

        $this->assertNotEquals('P@ssword123!', $user->password);
    }

    public function test_syncs_extra_permissions(): void
    {
        $payload = [
            'data' => [
                User::FIELD_NAME => 'Manager',
                User::FIELD_EMAIL => 'manager@example.com',
                User::FIELD_PASSWORD => 'P@ssword123!',
            ],
            'role' => Roles::MANAGER->value,
            'permissions' => [Permissions::CREATE_USER->value],
        ];

        $user = $this->service->handle($payload);

        $this->assertTrue($user->hasDirectPermission(Permissions::CREATE_USER->value));
        $this->assertTrue($user->hasRole(Roles::MANAGER->value));
    }

    public function test_password_confirmation_field_is_not_persisted(): void
    {
        $payload = [
            'data' => [
                User::FIELD_NAME => 'Confirm Test',
                User::FIELD_EMAIL => 'confirm@example.com',
                User::FIELD_PASSWORD => 'P@ssword123!',
                'password_confirmation' => 'P@ssword123!',
            ],
            'role' => Roles::USER->value,
            'permissions' => [],
        ];

        $user = $this->service->handle($payload);

        $this->assertArrayNotHasKey('password_confirmation', $user->getAttributes());
    }
}
