<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HariKerja>
 */
class HariKerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'day' => 100,
            'period' => fake()->dateTimeBetween('2026-01-01', '2026-03-01'),
            'salesman_id' => User::inRandomOrder()->value('id')
        ];
    }
}
