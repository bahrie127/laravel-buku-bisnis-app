<?php

use App\Models\User;
use App\Models\Category;

describe('Category Model Unit Tests', function () {
    it('can create category with factory', function () {
        $category = Category::factory()->create();

        expect($category)->toBeInstanceOf(Category::class);
        expect($category->name)->toBeString();
        expect($category->type)->toBeIn(['income', 'expense']);
    });

    it('enforces unique constraint at database level', function () {
        $user = User::factory()->create();

        Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Category',
            'type' => 'income'
        ]);

        expect(function () use ($user) {
            Category::factory()->create([
                'user_id' => $user->id,
                'name' => 'Test Category',
                'type' => 'income'
            ]);
        })->toThrow(\Exception::class);
    });

    it('allows same name for different users', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $category1 = Category::factory()->create([
            'user_id' => $user1->id,
            'name' => 'Test Category',
            'type' => 'income'
        ]);

        $category2 = Category::factory()->create([
            'user_id' => $user2->id,
            'name' => 'Test Category',
            'type' => 'income'
        ]);

        expect($category1->name)->toBe($category2->name);
        expect($category1->user_id)->not->toBe($category2->user_id);
    });

    it('allows same name for different types', function () {
        $user = User::factory()->create();

        $incomeCategory = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Category',
            'type' => 'income'
        ]);

        $expenseCategory = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Category',
            'type' => 'expense'
        ]);

        expect($incomeCategory->name)->toBe($expenseCategory->name);
        expect($incomeCategory->type)->not->toBe($expenseCategory->type);
    });

    it('has parent-child relationships', function () {
        $user = User::factory()->create();

        $parent = Category::factory()->create([
            'user_id' => $user->id,
            'type' => 'income'
        ]);

        $child = Category::factory()->create([
            'user_id' => $user->id,
            'type' => 'income',
            'parent_id' => $parent->id
        ]);

        expect($child->parent)->toBeInstanceOf(Category::class);
        expect($child->parent->id)->toBe($parent->id);
        expect($parent->children)->toHaveCount(1);
        expect($parent->children->first()->id)->toBe($child->id);
    });

    it('knows if it is parent or child', function () {
        $user = User::factory()->create();

        $parent = Category::factory()->create(['user_id' => $user->id]);
        $child = Category::factory()->create([
            'user_id' => $user->id,
            'parent_id' => $parent->id
        ]);

        expect($parent->isParent())->toBeTrue();
        expect($parent->isChild())->toBeFalse();
        expect($child->isParent())->toBeFalse();
        expect($child->isChild())->toBeTrue();
    });

    it('belongs to user', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        expect($category->user)->toBeInstanceOf(User::class);
        expect($category->user->id)->toBe($user->id);
    });
});
