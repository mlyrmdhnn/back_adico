<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Configuration;
use App\Models\Uom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(rand(1,3), true),
            'brand_id' => Brand::factory(),
            'configuration_id' => Configuration::factory(),
            'uom_id' => Uom::factory(),
            'satuan_uom' => fake()->numberBetween(1000, 20000),
            'karton' => fake()->numberBetween(1000,50000),
            'barcode' => fake()->numberBetween(10000,202344)
        ];
    }
}
