<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesTargets>
 */
class SalesTargetsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salesman_id' => User::inRandomOrder()->value('id'),
            'period_type' => fake()->randomElement(['daily', 'monthly']),
            'period_start' => fake()->dateTimeBetween('2026-01-01', '2026-03-01'),
            'target_value' => fake()->numberBetween(300000,50000000),
            'status' => 'active',
            'created_by' => 1
        ];
    }
}
