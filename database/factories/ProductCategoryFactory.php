<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parent_id' => null, // Initially no parent 
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'put_on_highlight' => $this->faker->boolean,
        ];
    }

    public function withParent($parentId)
    {
        return $this->state(['parent_id' => $parentId,]);
    }
}
