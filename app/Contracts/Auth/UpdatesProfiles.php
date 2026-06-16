<?php

namespace App\Contracts\Auth;

use App\Models\Auth\User;

interface UpdatesProfiles
{
    public function handle(User $user, array $payload): User;
}
