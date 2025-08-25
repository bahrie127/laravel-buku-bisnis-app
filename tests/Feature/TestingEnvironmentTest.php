<?php

use App\Models\User;
use App\Models\Category;

describe('Testing Environment Setup', function () {
    it('can create user with factory', function () {
        $user = User::factory()->create();

        expect($user)->toBeInstanceOf(User::class);
        expect($user->email)->toBeString();
        expect($user->name)->toBeString();
    });

    it('can use actingAsApi helper', function () {
        $user = User::factory()->create(['name' => 'Test User']);

        actingAsApi($user);

        $response = $this->getJson('/api/user');

        $response->assertOk();
        expect($response->json('name'))->toBe('Test User');
    });

    it('can use actingAsApiWithToken helper', function () {
        [$user, $token] = actingAsApiWithToken();

        expect($token)->toBeString();
        expect($user)->toBeInstanceOf(User::class);

        $response = $this->getJson('/api/user');

        $response->assertOk();
        expect($response->json('id'))->toBe($user->id);
    });

    it('can freeze and unfreeze time', function () {
        $time = freezeTime('2024-01-01 12:00:00');

        expect(now()->toDateTimeString())->toBe('2024-01-01 12:00:00');

        unfreezeTime();

        expect(now())->not->toBe($time);
    });

    it('can create category with unique constraint', function () {
        $user = User::factory()->create();

        $category1 = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Category',
            'type' => 'income',
        ]);

        expect($category1)->toBeInstanceOf(Category::class);

        // Test unique constraint
        expect(function () use ($user) {
            Category::factory()->create([
                'user_id' => $user->id,
                'name' => 'Test Category',
                'type' => 'income',
            ]);
        })->toThrow(\Exception::class);
    });

    it('has proper database isolation between tests', function () {
        expect(User::count())->toBe(0);
        expect(Category::count())->toBe(0);
    });
});
