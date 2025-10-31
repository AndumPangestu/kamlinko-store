<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word;
        return [
            'name' => $name,
            'code' => $this->faker->unique()->bothify('???-#####-???-#####'),
            'slug' => Str::slug($name),
            'value_percentage' => $this->faker->numberBetween(1, 50),
            'value_fixed' => $this->faker->numberBetween(10, 1000)  * 100,
            'quantity' => $this->faker->numberBetween(1, 1000),
            'used' => $this->faker->numberBetween(0, 100),
            'start_date' => $this->faker->dateTimeBetween('-7 day', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+7 day', '+2 month'),
            'description' => $this->faker->sentence,
            'terms' => $this->faker->sentence,
            'type' => $this->faker->randomElement(['ongkir', 'transaction_item']),
            'is_one_time_use' => $this->faker->boolean,
        ];
    }
}
