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
        $dummyWord = [
            'Pipa PVC',
            'Pipa Besi',
            'Pipa Galvanis',
            'Pipa HDPE',
            'Pipa PPR',
            'Cat Tembok',
            'Cat Kayu',
            'Cat Besi',
            'Cat Genteng',
            'Kran Air',
            'Kawat',
            'Kabel',
            'Wastafel',
            'Kloset',
            'Bak Cuci',
            'Bak Mandi',
            'Bak Air',
            'Bak Penampungan',
            'Bak Septic Tank',
            'Keramik Lantai',
            'Keramik Dinding',
            'Keramik Kamar Mandi',
            'Keramik Dapur',
            'Keramik Teras',
            'Keramik Taman',
            'Keramik Kolam Renang',
            'Keramik Kolam Ikan',                       
        ];
        return [
            'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id,
            'tag_id' => \App\Models\ProductTag::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::inRandomOrder()->first()->id,
            'name' => $name = $this->faker->unique()->randomElement($dummyWord),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence,
            'long_description' => $this->faker->paragraph,
            'put_on_highlight' => $this->faker->boolean(30), //30% chance to be put on highlight
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
