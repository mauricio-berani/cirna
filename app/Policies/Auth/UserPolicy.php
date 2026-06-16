<?php

namespace App\Policies\Auth;

use App\Enums\Auth\Permissions;
use App\Enums\Auth\Roles;
use App\Models\Auth\User;
use App\Policies\BasePolicy;

class UserPolicy extends BasePolicy
{
    public function mount(User $user): bool
    {
        return $user->can(Permissions::MOUNT_USER->value, User::class);
    }

    public function read(User $user): bool
    {
        return $user->can(Permissions::READ_USER->value, User::class);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::CREATE_USER->value, User::class);
    }

    public function update(User $user, User $target): bool
    {
        if (! $user->can(Permissions::UPDATE_USER->value, User::class)) {
            return false;
        }

        if ($target->hasRole(Roles::ADMINISTRATOR->value) && ! $user->hasRole(Roles::ADMINISTRATOR->value)) {
            return false;
        }

        return true;
    }

    public function delete(User $user, User $target): bool
    {
        if (! $user->can(Permissions::DELETE_USER->value, User::class)) {
            return false;
        }

        if ($user->is($target)) {
            return false;
        }

        if ($target->hasRole(Roles::ADMINISTRATOR->value) && ! $user->hasRole(Roles::ADMINISTRATOR->value)) {
            return false;
        }

        return true;
    }

    public function mountProfile(User $user): bool
    {
        return $user->can(Permissions::MOUNT_PROFILE->value, User::class);
    }

    public function updateProfile(User $user): bool
    {
        return $user->can(Permissions::UPDATE_PROFILE->value, User::class);
    }
}
