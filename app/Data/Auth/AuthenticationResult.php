<?php

namespace App\Data\Auth;

readonly class AuthenticationResult
{
    private function __construct(
        public bool $successful,
        public bool $throttled = false,
        public bool $twoFactorRequired = false,
        public int $availableIn = 0,
    ) {}

    public static function success(): self
    {
        return new self(true);
    }

    public static function twoFactorRequired(): self
    {
        return new self(false, twoFactorRequired: true);
    }

    public static function throttled(int $availableIn): self
    {
        return new self(false, true, availableIn: $availableIn);
    }

    public static function failed(): self
    {
        return new self(false);
    }
}
