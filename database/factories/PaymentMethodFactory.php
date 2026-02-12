<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['cash', 'tunai', 'transfer', 'kredit(7 haru)', 'kredit(14 hati)', 'kredit(21 hari)', 'kredit(30 hari)'])
        ];
    }
}
