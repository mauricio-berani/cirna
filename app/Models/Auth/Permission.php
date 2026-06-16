<?php

namespace App\Models\Auth;

use App\Traits\Uuid;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission implements PermissionContract
{
    use Uuid;
}
