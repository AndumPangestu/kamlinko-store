<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::has('userAddress')->inRandomOrder()->first();
        $transaction_status = ['Waiting Payment', 'Payment Received', 'Payment Rejected', 'On Process', 'On Delivery', 'Delivered', 'Completed', 'Cancelled'];
        $date = fake()->dateTimeBetween('-1 month', 'now');
        return [
            'user_id' => $user->id,
            'invoice_number' => fake()->regexify('(0[1-2])-[A-Z0-9]{8}'), // XX-YYYYYYYY format whereas X is store number and Y is the invoice number itself 
            'voucher_id' => fake()->boolean(80) == 1 ? \App\Models\Voucher::inRandomOrder()->first()->id : null,
            'user_address_id' => $user->userAddress->first()->id,
            'transaction_status' => fake()->randomElement($transaction_status),
            'payment_method' => fake()->randomElement(['Bank Transfer', 'Credit Card']),
            'payment_date' => $date,
            'delivery_method' => fake()->randomElement(['JNE', 'TIKI', 'Shop Courier', 'Pick Up']),
            'delivery_service' => fake()->randomElement(['Regular', 'Express']),
            'delivery_date' => fake()->dateTimeBetween('+1 days', '+10 days'),
            'delivery_fee' => 50000,
            'branch_store' => fake()->randomElement(['sinar abadi home centre', 'sinar abadi sindang barang']),
            'tracking_number' => in_array($transaction_status, ['On Delivery, Delivered, Completed'])  ? fake()->unique()->randomNumber(8) : null,
            'total_discount' => 0, // Will be calculated in the seeder
            'subtotal' => 0,      // Will be calculated in the seeder
            'tax' => 0,           // Will be calculated in the seeder
            'total' => 0,         // Will be calculated in the seeder
            'created_at' => $date,
        ];
    }
}
