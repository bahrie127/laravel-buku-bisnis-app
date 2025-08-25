<?php

use App\Models\User;
use App\Models\Category;

describe('Category API Testing Example', function () {
    it('can create categories via API', function () {
        $user = User::factory()->create();

        actingAsApi($user);

        $response = $this->postJson('/api/categories', [
            'name' => 'Test Income Category',
            'type' => 'income',
        ]);

        $response->assertCreated();
        expect($response->json())
            ->toHaveKey('data.id')
            ->toHaveKey('data.name', 'Test Income Category')
            ->toHaveKey('data.type', 'income')
            ->toHaveKey('data.user_id', $user->id);
    });

    it('validates required fields', function () {
        actingAsApi();

        $response = $this->postJson('/api/categories', []);

        $response->assertUnprocessable();
        expect($response->json())
            ->toBeValidationError('name')
            ->toBeValidationError('type');
    });

    it('enforces unique constraint', function () {
        $user = User::factory()->create();

        // Create first category
        Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Duplicate Category',
            'type' => 'income'
        ]);

        actingAsApi($user);

        // Try to create duplicate
        $response = $this->postJson('/api/categories', [
            'name' => 'Duplicate Category',
            'type' => 'income',
        ]);

        $response->assertUnprocessable();
        expect($response->json())
            ->toBeValidationError('name');
    });

    it('filters categories by user', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Category::factory()->create(['user_id' => $user1->id, 'name' => 'User 1 Category']);
        Category::factory()->create(['user_id' => $user2->id, 'name' => 'User 2 Category']);

        actingAsApi($user1);

        $response = $this->getJson('/api/categories');

        $response->assertOk();
        expect($response->json('data'))->toHaveCount(1);
        expect($response->json('data.0.name'))->toBe('User 1 Category');
    });

    it('can filter by type', function () {
        $user = User::factory()->create();

        Category::factory()->create(['user_id' => $user->id, 'type' => 'income']);
        Category::factory()->create(['user_id' => $user->id, 'type' => 'expense']);

        actingAsApi($user);

        $response = $this->getJson('/api/categories?type=income');

        $response->assertOk();
        expect($response->json('data'))->toHaveCount(1);
        expect($response->json('data.0.type'))->toBe('income');
    });

    it('includes parent relationship', function () {
        $user = User::factory()->create();

        $parent = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Parent Category',
            'type' => 'income'
        ]);

        $child = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Child Category',
            'type' => 'income',
            'parent_id' => $parent->id
        ]);

        actingAsApi($user);

        $response = $this->getJson("/api/categories/{$child->id}");

        $response->assertOk();
        expect($response->json())
            ->toHaveKey('data.parent.id', $parent->id)
            ->toHaveKey('data.parent.name', 'Parent Category');
    });

    it('validates parent belongs to user', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $otherUserCategory = Category::factory()->create([
            'user_id' => $user2->id,
            'type' => 'income'
        ]);

        actingAsApi($user1);

        $response = $this->postJson('/api/categories', [
            'name' => 'Test Category',
            'type' => 'income',
            'parent_id' => $otherUserCategory->id
        ]);

        $response->assertUnprocessable();
        expect($response->json())
            ->toBeValidationError('parent_id');
    });

    it('can update category', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        actingAsApi($user);

        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Updated Category Name',
            'type' => $category->type,
        ]);

        $response->assertOk();
        expect($response->json('data.name'))->toBe('Updated Category Name');
    });

    it('prevents unauthorized access', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $category = Category::factory()->create(['user_id' => $user2->id]);

        actingAsApi($user1);

        // Try to access other user's category (returns 404 for security)
        $response = $this->getJson("/api/categories/{$category->id}");
        $response->assertNotFound();

        // Try to update other user's category
        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Hacked Category'
        ]);
        $response->assertNotFound();

        // Try to delete other user's category
        $response = $this->deleteJson("/api/categories/{$category->id}");
        $response->assertNotFound();
    });

    it('uses time freezing for consistent testing', function () {
        freezeTime('2024-01-15 10:30:00');

        $user = User::factory()->create();
        actingAsApi($user);

        $response = $this->postJson('/api/categories', [
            'name' => 'Time Test Category',
            'type' => 'income',
        ]);

        $response->assertCreated();

        // Parse the response timestamp and check the date part
        $createdAt = $response->json('data.created_at');
        expect($createdAt)->toContain('2024-01-15');

        unfreezeTime();
    });
});
