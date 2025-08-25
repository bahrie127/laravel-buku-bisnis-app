<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' => \App\Models\Transaction::factory(),
            'filename' => fake()->word() . '.' . fake()->randomElement(['jpg', 'png', 'pdf', 'jpeg']),
            'original_name' => fake()->sentence(2) . '.' . fake()->randomElement(['jpg', 'png', 'pdf', 'jpeg']),
            'path' => 'attachments/' . fake()->uuid() . '.' . fake()->randomElement(['jpg', 'png', 'pdf', 'jpeg']),
            'size' => fake()->numberBetween(1024, 5242880), // 1KB to 5MB
            'mime_type' => fake()->randomElement(['image/jpeg', 'image/png', 'application/pdf']),
        ];
    }
}
