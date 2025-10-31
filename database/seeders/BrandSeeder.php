<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Semen Gresik',
                'description' => 'Semen Gresik adalah salah satu brand semen terbaik di Indonesia',
                'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id,
            ],
            [
                'name' => 'Semen Tiga Roda',
                'description' => 'Semen Tiga Roda adalah salah satu brand semen terbaik di Indonesia',
                'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id,
            ],
            [
                'name' => 'Semen Holcim',
                'description' => 'Semen Holcim adalah salah satu brand semen terbaik di Indonesia',
                'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id,
            ],
            [
                'name' => 'Cat Dulux',
                'description' => 'Cat Dulux adalah salah satu brand cat terbaik di Indonesia',
                'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id,
            ],
            [
                'name' => 'Cat Nippon',
                'description' => 'Cat Nippon adalah salah satu brand cat terbaik di Indonesia',
                'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id,
            ]
        ];

        $dummyLogo = [
            'https://img.freepik.com/free-vector/octagon-letter-gradient-logo_343694-1447.jpg',
            'https://cdn.pixabay.com/photo/2020/08/05/13/27/eco-5465459_640.png',
        ];

        $adminId = \App\Models\User::where('role', 'admin')->first()->id;
        foreach ($data as $brand) {
            $brand['created_by'] = $adminId;
            $brand['updated_by'] = $adminId;
            $result = \App\Models\Brand::create($brand);
            $result->addMediaFromUrl($dummyLogo[array_rand($dummyLogo)])->toMediaCollection('brand');
        }


        // Brand 1: Informa
        $brand = Brand::create([
            'id' => Str::uuid(),
            'category_id' => \App\Models\ProductCategory::inRandomOrder()->first()->id, // Contoh: Alat Bangunan
            'name' => 'Informa',
            'description' => 'Perusahaan ritel furnitur dan gaya hidup yang menjual berbagai produk untuk hunian, kantor, dan bisnis.',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        $brand->addMediaFromUrl('https://img.freepik.com/free-vector/octagon-letter-gradient-logo_343694-1447.jpg')
            ->toMediaCollection('brand');
    }
}
