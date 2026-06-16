<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_public_home_is_accessible_to_guests(): void
    {
        $this->withoutVite();

        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_the_application_redirects_guests_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_the_login_page_is_accessible(): void
    {
        $this->withoutVite();

        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_redis_retry_after_exceeds_horizon_timeout(): void
    {
        $this->assertGreaterThan(
            config('horizon.defaults.supervisor-1.timeout'),
            config('queue.connections.redis.retry_after')
        );
    }
}
