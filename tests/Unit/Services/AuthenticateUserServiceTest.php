<?php

namespace Tests\Unit\Services;

use App\Enums\Auth\Roles;
use App\Models\Auth\User;
use App\Services\Auth\AuthenticateUserService;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticateUserServiceTest extends TestCase
{
    private AuthenticateUserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthenticateUserService;
    }

    public function test_returns_success_for_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);
        $user->assignRole(Roles::USER->value);

        $result = $this->service->handle(
            ['email' => 'user@example.com', 'password' => 'password'],
            '127.0.0.1'
        );

        $this->assertTrue($result->successful);
        $this->assertFalse($result->throttled);
        $this->assertFalse($result->twoFactorRequired);
    }

    public function test_returns_failed_for_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $result = $this->service->handle(
            ['email' => 'user@example.com', 'password' => 'wrong-password'],
            '127.0.0.1'
        );

        $this->assertFalse($result->successful);
        $this->assertFalse($result->throttled);
    }

    public function test_returns_throttled_after_five_failed_attempts(): void
    {
        User::factory()->create(['email' => 'brute@example.com', 'password' => 'password']);

        for ($i = 0; $i < 5; $i++) {
            $this->service->handle(
                ['email' => 'brute@example.com', 'password' => 'wrong'],
                '10.0.0.1'
            );
        }

        $result = $this->service->handle(
            ['email' => 'brute@example.com', 'password' => 'password'],
            '10.0.0.1'
        );

        $this->assertFalse($result->successful);
        $this->assertTrue($result->throttled);
        $this->assertGreaterThan(0, $result->availableIn);
    }

    public function test_requires_two_factor_when_enabled(): void
    {
        $user = User::factory()->create([
            'email' => '2fa@example.com',
            'password' => 'password',
            'two_factor_secret' => encrypt('secret'),
            'two_factor_confirmed_at' => now(),
        ]);
        $user->assignRole(Roles::USER->value);

        $result = $this->service->handle(
            ['email' => '2fa@example.com', 'password' => 'password'],
            '127.0.0.1'
        );

        $this->assertFalse($result->successful);
        $this->assertTrue($result->twoFactorRequired);
    }

    public function test_clears_rate_limit_on_successful_login(): void
    {
        $user = User::factory()->create([
            'email' => 'clear@example.com',
            'password' => 'password',
        ]);
        $user->assignRole(Roles::USER->value);

        $this->service->handle(
            ['email' => 'clear@example.com', 'password' => 'wrong'],
            '127.0.0.1'
        );

        $this->service->handle(
            ['email' => 'clear@example.com', 'password' => 'password'],
            '127.0.0.1'
        );

        $throttleKey = strtolower('clear@example.com').'|127.0.0.1';
        $this->assertEquals(0, RateLimiter::attempts($throttleKey));
    }
}
