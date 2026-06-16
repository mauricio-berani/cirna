<?php

namespace App\Contracts\Auth;

use App\Models\Auth\User;

interface UpdatesUsers
{
    public function handle(User $user, array $payload, bool $syncAuthorization = true): User;
}
