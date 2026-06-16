<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthenticatesUsers;
use App\Data\Auth\AuthenticationResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticateUserService implements AuthenticatesUsers
{
    public function handle(array $credentials, string $ipAddress): AuthenticationResult
    {
        $throttleKey = $this->throttleKey($credentials['email'], $ipAddress);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            activity('auth')
                ->withProperties(['ip' => $ipAddress, 'email' => $credentials['email']])
                ->log('Login bloqueado por rate limiting');

            return AuthenticationResult::throttled(
                RateLimiter::availableIn($throttleKey)
            );
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($throttleKey, 60);

            activity('auth')
                ->withProperties(['ip' => $ipAddress, 'email' => $credentials['email']])
                ->log('Tentativa de login falhou');

            return AuthenticationResult::failed();
        }

        RateLimiter::clear($throttleKey);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        $user = Auth::user();

        activity('auth')
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['ip' => $ipAddress])
            ->log('Login realizado com sucesso');

        if ($user->hasTwoFactorEnabled()) {
            return AuthenticationResult::twoFactorRequired();
        }

        return AuthenticationResult::success();
    }

    private function throttleKey(string $email, string $ipAddress): string
    {
        return strtolower($email).'|'.$ipAddress;
    }
}
