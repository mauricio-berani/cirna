<?php

namespace App\Contracts\Navigation;

use Illuminate\Contracts\Auth\Authenticatable;

interface BuildsSidebarMenus
{
    public function handle(Authenticatable $user, string $currentRoute): array;
}
