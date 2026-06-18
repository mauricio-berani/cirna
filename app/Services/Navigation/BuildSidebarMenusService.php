<?php

namespace App\Services\Navigation;

use App\Contracts\Navigation\BuildsSidebarMenus;
use App\Enums\Auth\Permissions;
use Illuminate\Contracts\Auth\Authenticatable;

class BuildSidebarMenusService implements BuildsSidebarMenus
{
    public function handle(Authenticatable $user, string $currentRoute): array
    {
        $menus = [
            [
                'title' => __('interface.identification.dashboard.title'),
                'link' => route('dashboard'),
                'route' => 'dashboard',
                'icon' => 'o-home',
                'is_active' => $currentRoute === 'dashboard',
                'permission' => Permissions::MOUNT_DASHBOARD->value,
            ],
            [
                'title' => __('interface.identification.users.title'),
                'link' => route('users.index'),
                'route' => 'users.index',
                'permission' => Permissions::MOUNT_USER->value,
                'icon' => 'o-user',
                'is_active' => str_starts_with($currentRoute, 'users'),
            ],
            [
                'title' => __('interface.identification.applications.title'),
                'link' => route('candidaturas.index'),
                'route' => 'candidaturas.index',
                'permission' => Permissions::MOUNT_APPLICATION->value,
                'icon' => 'o-briefcase',
                'is_active' => str_starts_with($currentRoute, 'candidaturas'),
            ],
            [
                'title' => __('interface.identification.clients.title'),
                'link' => route('clientes.index'),
                'route' => 'clientes.index',
                'permission' => Permissions::MOUNT_CLIENT->value,
                'icon' => 'o-building-office-2',
                'is_active' => str_starts_with($currentRoute, 'clientes'),
            ],
            [
                'title' => __('interface.identification.settings.title'),
                'link' => route('settings'),
                'route' => 'settings',
                'permission' => Permissions::MOUNT_SETTING->value,
                'icon' => 'o-cog-6-tooth',
                'is_active' => str_starts_with($currentRoute, 'settings'),
            ],
        ];

        return collect($menus)
            ->filter(function (array $menu) use ($user) {
                if (isset($menu['submenus'])) {
                    $menu['submenus'] = collect($menu['submenus'])
                        ->filter(fn (array $submenu) => $user->can($submenu['permission']))
                        ->values()
                        ->all();

                    return count($menu['submenus']) > 0;
                }

                return $user->can($menu['permission']);
            })
            ->values()
            ->all();
    }
}
