<?php

namespace Tests\Unit\Services;

use App\Enums\Auth\Permissions;
use App\Enums\Auth\Roles;
use App\Models\Auth\User;
use App\Services\Auth\UpdateUserService;
use Tests\TestCase;

class UpdateUserServiceTest extends TestCase
{
    private UpdateUserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UpdateUserService;
    }

    public function test_updates_user_name_and_email(): void
    {
        $user = $this->makeUser();

        $this->service->handle($user, [
            'data' => [
                User::FIELD_NAME => 'Updated Name',
                User::FIELD_EMAIL => 'updated@example.com',
            ],
            'role' => Roles::USER->value,
            'permissions' => [],
        ]);

        $this->assertDatabaseHas('users', [
            User::FIELD_ID => $user->id,
            User::FIELD_NAME => 'Updated Name',
            User::FIELD_EMAIL => 'updated@example.com',
        ]);
    }

    public function test_updates_password_when_provided(): void
    {
        $user = $this->makeUser();
        $originalPassword = $user->password;

        $this->service->handle($user, [
            'data' => [
                User::FIELD_NAME => $user->name,
                User::FIELD_EMAIL => $user->email,
                User::FIELD_PASSWORD => 'NewP@ssword456!',
            ],
            'role' => Roles::USER->value,
            'permissions' => [],
        ]);

        $user->refresh();

        $this->assertNotEquals($originalPassword, $user->password);
    }

    public function test_does_not_change_password_when_not_provided(): void
    {
        $user = $this->makeUser();
        $originalPassword = $user->password;

        $this->service->handle($user, [
            'data' => [
                User::FIELD_NAME => 'New Name',
                User::FIELD_EMAIL => $user->email,
            ],
            'role' => Roles::USER->value,
            'permissions' => [],
        ]);

        $user->refresh();

        $this->assertEquals($originalPassword, $user->password);
    }

    public function test_syncs_roles_when_sync_authorization_is_true(): void
    {
        $user = $this->makeUser(Roles::USER);

        $this->service->handle($user, [
            'data' => [User::FIELD_NAME => $user->name, User::FIELD_EMAIL => $user->email],
            'role' => Roles::ADMINISTRATOR->value,
            'permissions' => [],
        ], syncAuthorization: true);

        $this->assertTrue($user->fresh()->hasRole(Roles::ADMINISTRATOR->value));
    }

    public function test_does_not_sync_roles_when_sync_authorization_is_false(): void
    {
        $user = $this->makeUser(Roles::USER);

        $this->service->handle($user, [
            'data' => [User::FIELD_NAME => $user->name, User::FIELD_EMAIL => $user->email],
            'role' => Roles::ADMINISTRATOR->value,
            'permissions' => [],
        ], syncAuthorization: false);

        $this->assertFalse($user->fresh()->hasRole(Roles::ADMINISTRATOR->value));
        $this->assertTrue($user->fresh()->hasRole(Roles::USER->value));
    }

    public function test_syncs_direct_permissions(): void
    {
        $user = $this->makeUser();

        $this->service->handle($user, [
            'data' => [User::FIELD_NAME => $user->name, User::FIELD_EMAIL => $user->email],
            'role' => Roles::USER->value,
            'permissions' => [Permissions::CREATE_USER->value],
        ]);

        $this->assertTrue($user->fresh()->hasDirectPermission(Permissions::CREATE_USER->value));
    }
}
