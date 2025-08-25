<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // The home route redirects to /admin, so we expect a 302 redirect
        $response->assertStatus(302);
        $response->assertRedirect('/admin');
    }
}
