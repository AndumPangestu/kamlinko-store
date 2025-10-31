<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductTag;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'New',
                'description' => 'New Arrival',
            ],
            [
                'name' => 'Sale',
                'description' => 'On Sale',
            ],
            [
                'name' => 'Popular',
                'description' => 'Popular Product',
            ],
            [
                'name' => 'Featured',
                'description' => 'Featured Product',
            ]
        ];
        $adminId = \App\Models\User::where('role', 'admin')->first()->id;
        foreach ($data as $tag) {
            $tag['created_by'] = $adminId;
            $tag['updated_by'] = $adminId;
            ProductTag::create($tag);
        }
    }
}
