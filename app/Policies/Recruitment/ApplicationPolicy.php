<?php

namespace App\Policies\Recruitment;

use App\Enums\Auth\Permissions;
use App\Models\Auth\User;
use App\Models\Recruitment\Application;
use App\Policies\BasePolicy;

class ApplicationPolicy extends BasePolicy
{
    public function mount(User $user): bool
    {
        return $user->can(Permissions::MOUNT_APPLICATION->value, User::class);
    }

    public function read(User $user): bool
    {
        return $user->can(Permissions::READ_APPLICATION->value, User::class);
    }

    public function delete(User $user, Application $target): bool
    {
        return $user->can(Permissions::DELETE_APPLICATION->value, User::class);
    }
}
