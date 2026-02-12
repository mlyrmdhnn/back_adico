<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'address' =>  fake()->address(),
            'customer_id' => Customer::inRandomOrder()->value('id'),
            'created_date' => fake()->dateTimeBetween('2026-01-01', '2026-02-28'),
            'salesman_id' => User::inRandomOrder()->value('id')
        ];
    }
}
