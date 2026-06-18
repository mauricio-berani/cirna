<?php

namespace Database\Seeders;

use App\Enums\Auth\Permissions;
use App\Enums\Auth\Roles;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionsUser = [
            Permissions::MOUNT_DASHBOARD->value,
            Permissions::MOUNT_PROFILE->value,
            Permissions::UPDATE_PROFILE->value,
        ];

        $permissionsManager = $permissionsUser;

        $permissionsAdministrator = array_merge($permissionsManager, [
            Permissions::MOUNT_USER->value,
            Permissions::READ_USER->value,
            Permissions::CREATE_USER->value,
            Permissions::UPDATE_USER->value,
            Permissions::DELETE_USER->value,
            Permissions::MOUNT_APPLICATION->value,
            Permissions::READ_APPLICATION->value,
            Permissions::DELETE_APPLICATION->value,
            Permissions::MOUNT_CLIENT->value,
            Permissions::READ_CLIENT->value,
            Permissions::CREATE_CLIENT->value,
            Permissions::UPDATE_CLIENT->value,
            Permissions::DELETE_CLIENT->value,
            Permissions::MOUNT_SETTING->value,
            Permissions::UPDATE_SETTING->value,
            Permissions::VIEW_HORIZON->value,
        ]);

        $permissions = $permissionsAdministrator;

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roles = [
            Roles::USER->value => $permissionsUser,
            Roles::MANAGER->value => $permissionsManager,
            Roles::ADMINISTRATOR->value => $permissionsAdministrator,
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::updateOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($rolePermissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
