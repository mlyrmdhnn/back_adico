<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Requests;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\request_items>
 */
class RequestItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'request_id' => Requests::inRandomOrder()->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'qty' => fake()->numberBetween(20,50),
        ];
    }
}
