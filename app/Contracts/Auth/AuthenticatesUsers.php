<?php

namespace App\Contracts\Auth;

use App\Data\Auth\AuthenticationResult;

interface AuthenticatesUsers
{
    public function handle(array $credentials, string $ipAddress): AuthenticationResult;
}
