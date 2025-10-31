<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voucherTypes = [
            [
                'name' => 'Discount',
                'description' => 'Discount voucher',
            ],
            [
                'name' => 'Cashback',
                'description' => 'Cashback voucher',
            ],
            [
                'name' => 'Free Shipping',
                'description' => 'Free shipping voucher',
            ],
        ];

        foreach ($voucherTypes as $voucherType) {
            \App\Models\VoucherType::create($voucherType);
        }
    }
}
