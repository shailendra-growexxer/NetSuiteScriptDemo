<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = fake()->unique()->words(3, true);
        return [
            'sku' => strtoupper(Str::slug($name)).'-'.fake()->unique()->numerify('####'),
            'barcode' => fake()->ean13(),
            'name' => ucfirst($name),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 999),
            'cost' => fake()->randomFloat(2, 2, 800),
            'stock' => fake()->numberBetween(0, 1000),
            'uom' => fake()->randomElement(['EA', 'BOX', 'CS', 'KG']),
            'category' => fake()->randomElement(['Electronics', 'Home', 'Sports', 'Toys', 'Beauty', 'Grocery']),
            'brand' => fake()->company(),
            'is_active' => fake()->boolean(90),
            'attributes' => [
                'color' => fake()->safeColorName(),
                'size' => fake()->randomElement(['S','M','L','XL']),
                'weight_kg' => fake()->randomFloat(2, 0.1, 20),
            ],
        ];
    }
}
