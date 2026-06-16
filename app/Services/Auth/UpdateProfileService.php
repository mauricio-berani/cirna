<?php

namespace App\Services\Auth;

use App\Contracts\Auth\UpdatesProfiles;
use App\Models\Auth\User;
use App\Traits\ManagesFilesTrait;
use Illuminate\Support\Facades\DB;

class UpdateProfileService implements UpdatesProfiles
{
    use ManagesFilesTrait;

    public string $filePath = 'avatars';

    public function handle(User $user, array $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $data = $payload['data'];
            $password = $data[User::FIELD_PASSWORD] ?? null;
            unset($data[User::FIELD_PASSWORD], $data['password_confirmation']);

            $oldAvatarPath = $user->{User::FIELD_AVATAR};

            $user->update($data);

            if ($password) {
                $user->forceFill([User::FIELD_PASSWORD => $password])->save();
            }

            if ($payload['avatar']) {
                $path = $this->uploadFile($payload['avatar'], $oldAvatarPath, true);
                $user->update([User::FIELD_AVATAR => $path]);
            }

            return $user->refresh();
        });
    }
}
