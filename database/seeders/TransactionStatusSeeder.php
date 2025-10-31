<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionStatuses = [
            [
                'name' => 'Waiting Payment',
                'description' => 'Transaction is waiting for payment',
            ],

            [
                'name' => 'Payment Received',
                'description' => 'Payment is received',
            ],
            [
                'name' => 'On Process',
                'description' => 'Transaction is on process',
            ],
            [
                'name' => 'On Delivery',
                'description' => 'Transaction is on delivery',
            ],
            [
                'name' => 'Completed',
                'description' => 'Transaction is completed',
            ],
            [
                'name' => 'Cancelled',
                'description' => 'Transaction is cancelled',
            ],
        ];

        foreach ($transactionStatuses as $status) {
            \App\Models\TransactionStatus::create($status);
        }
    }
}
