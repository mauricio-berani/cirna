<?php

namespace App\Policies\Common;

use App\Enums\Auth\Permissions;
use App\Models\Auth\User;
use App\Policies\BasePolicy;

class ClientPolicy extends BasePolicy
{
    public function mount(User $user): bool
    {
        return $user->can(Permissions::MOUNT_CLIENT->value, User::class);
    }

    public function read(User $user): bool
    {
        return $user->can(Permissions::READ_CLIENT->value, User::class);
    }

    public function create(User $user): bool
    {
        return $user->can(Permissions::CREATE_CLIENT->value, User::class);
    }

    public function update(User $user): bool
    {
        return $user->can(Permissions::UPDATE_CLIENT->value, User::class);
    }

    public function delete(User $user): bool
    {
        return $user->can(Permissions::DELETE_CLIENT->value, User::class);
    }
}
