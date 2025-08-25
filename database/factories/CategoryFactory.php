<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['income', 'expense']);

        $incomeCategories = [
            'Salary',
            'Freelance',
            'Investment',
            'Business',
            'Gift',
            'Bonus',
            'Commission'
        ];

        $expenseCategories = [
            'Food & Dining',
            'Transportation',
            'Shopping',
            'Entertainment',
            'Bills & Utilities',
            'Healthcare',
            'Education',
            'Travel',
            'Insurance',
            'Groceries',
            'Rent',
            'Phone'
        ];

        $categories = $type === 'income' ? $incomeCategories : $expenseCategories;

        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->randomElement($categories),
            'type' => $type,
            'parent_id' => null, // Will be set manually for nested categories
        ];
    }
}
