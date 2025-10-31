<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductType>
 */
class ProductTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'color' => $this->faker->hexColor(),
            'price' => $this->faker->randomFloat(0, 100, 10000) * 100, // round to nearest 100
            'discount_fixed' => $this->faker->randomFloat(0, 10, 100) * 100,
            'discount_percentage' => $this->faker->randomFloat(2, 0, 100),
            'weight' => $this->faker->randomFloat(2, 0, 100),
            'description' => $this->faker->sentence,
            'sku' => $this->faker->unique()->bothify('???-#####-???'),
            'stock' => $this->faker->randomNumber(2),
            'total_sales' => $this->faker->randomNumber(2),
        ];
    }
}
