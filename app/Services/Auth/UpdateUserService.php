<?php

namespace App\Services\Auth;

use App\Contracts\Auth\UpdatesUsers;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;

class UpdateUserService implements UpdatesUsers
{
    public function handle(User $user, array $payload, bool $syncAuthorization = true): User
    {
        return DB::transaction(function () use ($user, $payload, $syncAuthorization) {
            $data = $payload['data'];
            $password = $data[User::FIELD_PASSWORD] ?? null;
            unset($data[User::FIELD_PASSWORD], $data['password_confirmation']);

            $user->update($data);

            if ($password) {
                $user->forceFill([User::FIELD_PASSWORD => $password])->save();
            }

            if ($syncAuthorization) {
                $user->syncRoles([$payload['role']]);
                $user->syncPermissions($payload['permissions']);
            }

            return $user->refresh();
        });
    }
}
