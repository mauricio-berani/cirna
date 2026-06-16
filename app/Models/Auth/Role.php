<?php

namespace App\Models\Auth;

use App\Traits\Uuid;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements RoleContract
{
    use Uuid;
}
