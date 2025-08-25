<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->randomElement([
                'Cash Wallet',
                'BCA Checking',
                'Mandiri Savings',
                'Dana E-Wallet',
                'GoPay',
                'OVO',
                'BNI Credit Card',
                'Emergency Fund'
            ]),
            'type' => fake()->randomElement(['cash', 'bank', 'ewallet', 'other']),
            'starting_balance' => fake()->randomFloat(2, 0, 10000000),
            'is_active' => fake()->boolean(90),
        ];
    }
}
