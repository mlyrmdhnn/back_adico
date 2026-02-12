<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OmsetSalesman>
 */
class OmsetSalesmanFactory extends Factory
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
            'omset_date' => fake()->dateTimeBetween('2026-01-01', '2026-03-01'),
            'omset_value' => fake()->numberBetween(10000000,20000000),
        ];
    }
}
