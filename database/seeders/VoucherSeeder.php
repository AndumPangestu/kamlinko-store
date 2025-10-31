<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = \App\Models\User::where('role', 'admin')->first()->id;
        $vouchers = [
            [
                'Name' => 'ONGKIR GRATIS',
                'slug' => 'ongkir-gratis',
                'Code' => 'GRATISONGKIR',
                'Description' => 'GRATIS ONGKIR UNTUK SELURUH BOGOR',
                'value_fixed' => 0,
                'value_percentage' => 100,
                'minimum_transaction_value' => 100000,
                'quantity' => 100,
                'used' => 0,
                'terms' => 'Hanya berlaku untuk pengiriman ke Bogor',
                'category' => 'offline',
                'type' => 'ongkir',
                'is_one_time_use' => 0,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ],
            [
                'Name' => 'POTONGAN GELEGAR',
                'slug' => 'potongan-gelegar',
                'Code' => 'BXUNLIMITED',
                'Description' => 'POTONGAN UP TO 50% UNTUK SEMUA PRODUK',
                'value_fixed' => 100000,
                'value_percentage' => 50,
                'minimum_transaction_value' => 100000,
                'quantity' => 100,
                'used' => 0,
                'terms' => 'Hanya berlaku untuk 100 orang tercepat',
                'category' => 'offline',
                'type' => 'transaction_item',
                'is_one_time_use' => 0,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ],
            [
                'Name' => 'Ulang Tahun ke 50 BX',
                'slug' => 'ulang-tahun-ke-50-bx',
                'Code' => 'HBDBX',
                'Description' => 'HARI BAHAGIA BX, DAPATKAN POTONGAN 50%',
                'value_fixed' => 0,
                'value_percentage' => 50,
                'minimum_transaction_value' => 100000,
                'quantity' => 50,
                'used' => 0,
                'terms' => 'Hanya berlaku untuk 50 orang tercepat',
                'category' => 'offline',
                'type' => 'transaction_item',
                'is_one_time_use' => 1,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
            ],
        ];

        $vouchersMedia = 'https://designcuts.b-cdn.net/wp-content/uploads/2023/12/6NHSGPjP-gift-voucher-mockups-1.jpg';

        foreach ($vouchers as $voucher) {
            $voucher['created_by'] = $adminId;
            $voucher['updated_by'] = $adminId;
            $voucherModel = \App\Models\Voucher::create($voucher);

            $voucherModel->addMediaFromUrl($vouchersMedia)->preservingOriginal()
                ->toMediaCollection('voucher/banner');
        }
    }
}
