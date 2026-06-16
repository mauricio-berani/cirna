<?php

namespace App\Policies\Common;

use App\Enums\Auth\Permissions;
use App\Models\Auth\User;
use App\Policies\BasePolicy;

class SettingPolicy extends BasePolicy
{
    public function mount(User $user): bool
    {
        return $user->can(Permissions::MOUNT_SETTING->value, User::class);
    }

    public function update(User $user): bool
    {
        return $user->can(Permissions::UPDATE_SETTING->value, User::class);
    }
}
