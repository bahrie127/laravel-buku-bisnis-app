<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Set up an authenticated user for API testing with Sanctum
     */
    protected function actingAsApi(User $user = null): static
    {
        if (!$user) {
            $user = User::factory()->create();
        }

        Sanctum::actingAs($user, ['*']);

        return $this;
    }

    /**
     * Set up an authenticated user for API testing with token
     */
    protected function actingAsApiWithToken(User $user = null): array
    {
        if (!$user) {
            $user = User::factory()->create();
        }

        $token = $user->createToken('test-token')->plainTextToken;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);

        return [$user, $token];
    }
}
