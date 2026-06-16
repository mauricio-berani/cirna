<?php

namespace App\Policies;

use App\Enums\Auth\Permissions;
use App\Models\Auth\User;

class BasePolicy
{
    protected function can(User $user, Permissions|string $permission): bool
    {
        $permissionValue = $permission instanceof Permissions ? $permission->value : $permission;

        return $user->can($permissionValue, User::class);
    }
}
