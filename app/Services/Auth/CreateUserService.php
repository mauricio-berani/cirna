<?php

namespace App\Services\Auth;

use App\Contracts\Auth\CreatesUsers;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;

class CreateUserService implements CreatesUsers
{
    public function handle(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $data = $payload['data'];
            $password = $data[User::FIELD_PASSWORD] ?? null;
            unset($data[User::FIELD_PASSWORD], $data['password_confirmation']);

            /** @var User $user */
            $user = new User($data);
            $user->forceFill([User::FIELD_PASSWORD => $password])->save();

            $user->syncRoles([$payload['role']]);
            $user->syncPermissions($payload['permissions']);

            return $user;
        });
    }
}
