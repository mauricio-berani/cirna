<?php

namespace Database\Seeders;

use App\Enums\Auth\Roles;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdministratorUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('admin.password');

        if (blank($password)) {
            throw new RuntimeException('ADMIN_PASSWORD não configurada. Defina a senha inicial do administrador via variável de ambiente.');
        }

        if (
            strlen($password) < 12
            || ! preg_match('/[A-Z]/', $password)
            || ! preg_match('/[a-z]/', $password)
            || ! preg_match('/[0-9]/', $password)
            || ! preg_match('/[^A-Za-z0-9]/', $password)
        ) {
            throw new RuntimeException(
                'ADMIN_PASSWORD deve ter pelo menos 12 caracteres com letras maiúsculas, minúsculas, números e caracteres especiais.'
            );
        }

        $administratorRole = Role::where('name', Roles::ADMINISTRATOR->value)->firstOrFail();

        $administratorUser = User::firstOrNew([
            User::FIELD_EMAIL => config('admin.email'),
        ]);

        $administratorUser->fill([
            User::FIELD_NAME => config('admin.name'),
            User::FIELD_PHONE => config('admin.phone'),
        ]);

        $administratorUser->forceFill([
            User::FIELD_PASSWORD => $password,
        ])->save();

        $administratorUser->syncRoles([$administratorRole]);
    }
}
