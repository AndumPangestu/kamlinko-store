<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionItems>
 */
class TransactionItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productType = \App\Models\ProductType::inRandomOrder()->first();

        $quantity = fake()->numberBetween(1, 5);
        $price = $productType->price;
        $discountFixed = $productType->discount_fixed;
        $discountPercentage = $productType->discount_percentage;

        // Calculate discount
        $discountAmount = 0;
        if ($discountPercentage > 0) {
            $discountAmount = $price * ($discountPercentage / 100);
            if ($discountFixed > 0) {
                $discountAmount = min($discountAmount, $discountFixed); // Apply the minimum discount
            }
        } elseif ($discountFixed > 0) {
            $discountAmount = $discountFixed;
        }

        // Calculate discounted price
        $discountedPrice = $price - $discountAmount;

        // Calculate subtotal
        $subtotal = $discountedPrice * $quantity;

        return [
            'transaction_id' => null, // Will be filled in seeder
            'product_type_id' => $productType->id,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
    }
}
