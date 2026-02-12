<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\requests>
 */
class RequestsFactory extends Factory
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
            'store_id' => Store::inRandomOrder()->value('id'),
            'manager_id' => 1,
            'payment_method_id' => PaymentMethod::inRandomOrder()->value('id'),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
