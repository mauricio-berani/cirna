<?php

namespace App\Contracts\Auth;

use App\Models\Auth\User;

interface CreatesUsers
{
    public function handle(array $payload): User;
}
