<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\Login;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_a_user_can_log_in_through_the_livewire_component(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        Livewire::test(Login::class)
            ->set('form.email', 'admin@example.com')
            ->set('form.password', 'password')
            ->call('login');

        $this->assertAuthenticatedAs($user);
    }

    public function test_local_content_security_policy_allows_vite_image_assets(): void
    {
        app()->detectEnvironment(fn (): string => 'local');

        $response = $this->get(route('login'))
            ->assertOk();

        $contentSecurityPolicy = (string) $response->headers->get('Content-Security-Policy');

        $this->assertStringContainsString('img-src', $contentSecurityPolicy);
        $this->assertStringContainsString('http://localhost:5173', $contentSecurityPolicy);
    }
}
