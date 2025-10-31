<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = \App\Models\User::where('role', 'admin')->first()->id;

        $product = Product::create([
            'category_id' => \App\Models\ProductCategory::where('name', 'Electrical')->first()->id,
            'tag_id' => \App\Models\ProductTag::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::where("name", "Informa")->first()->id,
            'name' => 'Kels Ollie Pengukus Makanan Mini',
            'slug' => 'kels-ollie-pengukus-makanan-mini',
            'description' => 'Daya: 350 watt, Material: plastik, Voltase: 220 volt',
            'long_description' => 'Kapasitas besar Memiliki 2 wadah susun Memiliki tutup transparan Gagang anti panas Mudah digunakan Mudah dibersihkan Colokan dan kabel VDE Pelat pemanas berlapis keramik, tidak berkerak dan mudah dibersihkan Power offi otomatis untuk mencegah pembakaran kering Lubang telur dirancang khusus untuk grid pengukusan, yang dapat mengukus 12 butir telur Food-grade Tahan suhu tinggi tanpa bau Uap besar mengalir di sekitar makanan yang dikukus sehingga makanan di tingkat bagian atas juga dapat matang Cocok digunakan untuk mengukus bakpao, siomay, dan makanan bayi Daya: 350 watt Dimensi produk: 15.4 cm x 20.7 cm x 20.2 cm Isi kemasan: 1 pc alat pengukus Jenis: food steamer Material: plastik Pengaturan suhu maksimal: 170 °C Sumber daya: listrik Voltase: 220 volt Warranty Service: 12 Months Warna: Putih Dimensi Kemasan: 22.4 x 19.1 x 25.0 cm Berat: 1.04 kg SKU: 10566312 Nama Komoditas: KELS OLLIE MINI STEAMER SET WHITE',
            'put_on_highlight' => true,
            'views' => rand(100, 10000),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        $productType = ProductType::create([
            'product_id' => $product->id,
            'name' => 'Putih',
            'sku' => 'ol-10566312',
            'description' => 'Kels Ollie Pengukus Makanan Mini warna putih',
            'price' => 100000,
            'color' => '#ffffff',
            'discount_percentage' => 20,
            'discount_fixed' => 10000,
            'stock' => 100,
            'weight' => 1.04,
            'total_sales' => 20,
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);


        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10566312_1.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10566312_2.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10566312_3.jpg')->toMediaCollection('product-types');


        $productType = ProductType::create([
            'product_id' => $product->id,
            'name' => 'Hijau',
            'sku' => 'ol-10566312',
            'description' => 'Kels Ollie Pengukus Makanan Mini warna hijau',
            'price' => 100000,
            'color' => '#00ff00',
            'discount_percentage' => 20,
            'discount_fixed' => 10000,
            'stock' => 10,
            'weight' => 1.04,
            'total_sales' => 120,
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);


        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10566313_1.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10566313_2.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10566313_3.jpg')->toMediaCollection('product-types');

        $product = Product::create([
            'category_id' => \App\Models\ProductCategory::where('name', 'Electrical')->first()->id,
            'tag_id' => \App\Models\ProductTag::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::where("name", "Informa")->first()->id,
            'name' => 'Kels 10 Ltr Mini Steam Oven Toaster',
            'slug' => 'kels-10-ltr-mini-steam-oven-toaster',
            'description' => 'Daya: 1300 watt, Material: plastik, Voltase: 220 volt',
            'long_description' => 'Menggunakan sensor kontrol sentuh LED display Teknologi pemanas ganda dari atas dan bawah 7 auto menu (Toast, Cheese Toast, French Baguette, Croissant, 170C, 200℃, 230℃) Manual setting (waktu maksimal 30 menit, temp maksimal 230℃) Bagian kenop dan foot oven memiliki aksen kayu Material : metal, plastik, kaca Syarat dan ketentuan klaim garansi, klik di sini Daya: 1300 watt Dimensi produk: 27.3 cm x 21.8 cm x 12.8 cm Kapasitas: 10 L Max temperature: 230 °C Voltase: 220 volt Warranty Service: 12 Months Warna: Lainnya Dimensi Kemasan: 39.0 x 39.0 x 27.0 cm Berat: 6 kg SKU: 10467931 Nama Komoditas: KELS MINI STEAM OVEN TOASTER 10L WHITE',
            'put_on_highlight' => true,
            'views' => rand(100, 10000),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        $productType = ProductType::create([
            'product_id' => $product->id,
            'name' => 'Putih',
            'sku' => 'ol-10566312',
            'description' => 'KKels 10 Ltr Mini Steam Oven Toaster warna putih',
            'price' => 500000,
            'color' => '#ffffff',
            'discount_percentage' => 0,
            'discount_fixed' => 20000,
            'stock' => 100,
            'weight' => 5.04,
            'total_sales' => 202,
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);


        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10467931_1.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10467931_2.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10467931_3.jpg')->toMediaCollection('product-types');



        $product = Product::create([
            'category_id' => \App\Models\ProductCategory::where('name', 'Electrical')->first()->id,
            'tag_id' => \App\Models\ProductTag::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::where("name", "Informa")->first()->id,
            'name' => 'Sharp 42 Inci Led Smart Tv 2t-c42eg1i-sb',
            'slug' => 'sharp-42-inci-led-smart-tv-2t-c42eg1i-sb',
            'description' => 'Daya: 1300 watt, Material: plastik, Voltase: 220 volt',
            'long_description' => 'Menggunakan sensor kontrol sentuh LED display Teknologi pemanas ganda dari atas dan bawah 7 auto menu (Toast, Cheese Toast, French Baguette, Croissant, 170C, 200℃, 230℃) Manual setting (waktu maksimal 30 menit, temp maksimal 230℃) Bagian kenop dan foot oven memiliki aksen kayu Material : metal, plastik, kaca Syarat dan ketentuan klaim garansi, klik di sini Daya: 1300 watt Dimensi produk: 27.3 cm x 21.8 cm x 12.8 cm Kapasitas: 10 L Max temperature: 230 °C Voltase: 220 volt Warranty Service: 12 Months Warna: Lainnya Dimensi Kemasan: 39.0 x 39.0 x 27.0 cm Berat: 6 kg SKU: 10467931 Nama Komoditas: KELS MINI STEAM OVEN TOASTER 10L WHITE',
            'put_on_highlight' => true,
            'views' => rand(100, 10000),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        $productType = ProductType::create([
            'product_id' => $product->id,
            'name' => 'Hitam',
            'sku' => 'ol-10566312',
            'description' => 'Sharp 42 Inci Led Smart Tv 2t-c42eg1i-sb warna hitam',
            'price' => 5000000,
            'color' => '#000000',
            'discount_percentage' => 10,
            'discount_fixed' => 0,
            'stock' => 100,
            'weight' => 10.04,
            'total_sales' => 22,
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);


        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10538093_1.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10538093_2.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10538093_3.jpg')->toMediaCollection('product-types');



        $product = Product::create([
            'category_id' => \App\Models\ProductCategory::where('name', 'Electrical')->first()->id,
            'tag_id' => \App\Models\ProductTag::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::where("name", "Informa")->first()->id,
            'name' => 'Kels 8 Kg Mesin Cuci Front Loading',
            'slug' => 'kels-8-kg-mesin-cuci-front-loading',
            'description' => 'Daya: 1300 watt, Material: plastik, Voltase: 220 volt',
            'long_description' => 'Menggunakan sensor kontrol sentuh LED display Teknologi pemanas ganda dari atas dan bawah 7 auto menu (Toast, Cheese Toast, French Baguette, Croissant, 170C, 200℃, 230℃) Manual setting (waktu maksimal 30 menit, temp maksimal 230℃) Bagian kenop dan foot oven memiliki aksen kayu Material : metal, plastik, kaca Syarat dan ketentuan klaim garansi, klik di sini Daya: 1300 watt Dimensi produk: 27.3 cm x 21.8 cm x 12.8 cm Kapasitas: 10 L Max temperature: 230 °C Voltase: 220 volt Warranty Service: 12 Months Warna: Lainnya Dimensi Kemasan: 39.0 x 39.0 x 27.0 cm Berat: 6 kg SKU: 10467931 Nama Komoditas: KELS MINI STEAM OVEN TOASTER 10L WHITE',
            'put_on_highlight' => true,
            'views' => rand(100, 10000),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        $productType = ProductType::create([
            'product_id' => $product->id,
            'name' => 'Putih',
            'sku' => 'ol-10566312',
            'description' => 'Kels 8 Kg Mesin Cuci Front Loading - Putih warna putih',
            'price' => 5000000,
            'color' => '#ffffff',
            'discount_percentage' => 10,
            'discount_fixed' => 100000,
            'stock' => 10,
            'weight' => 20.04,
            'total_sales' => 25,
            'created_by' => $adminId,
            'updated_by' => $adminId,

        ]);


        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10457284_1.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10457284_2.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10457284_3.jpg')->toMediaCollection('product-types');

        $product = Product::create([
            'category_id' => \App\Models\ProductCategory::where('name', 'Electrical')->first()->id,
            'tag_id' => \App\Models\ProductTag::inRandomOrder()->first()->id,
            'brand_id' => \App\Models\Brand::where("name", "Informa")->first()->id,
            'name' => 'Sharp Air Conditioner 1 Pk Ah/au-xp10yhy',
            'slug' => 'sharp-air-conditioner-1-pk-ahau-xp10yhy',
            'description' => 'Daya: 1300 watt, Material: plastik, Voltase: 220 volt',
            'long_description' => 'Menggunakan sensor kontrol sentuh LED display Teknologi pemanas ganda dari atas dan bawah 7 auto menu (Toast, Cheese Toast, French Baguette, Croissant, 170C, 200℃, 230℃) Manual setting (waktu maksimal 30 menit, temp maksimal 230℃) Bagian kenop dan foot oven memiliki aksen kayu Material : metal, plastik, kaca Syarat dan ketentuan klaim garansi, klik di sini Daya: 1300 watt Dimensi produk: 27.3 cm x 21.8 cm x 12.8 cm Kapasitas: 10 L Max temperature: 230 °C Voltase: 220 volt Warranty Service: 12 Months Warna: Lainnya Dimensi Kemasan: 39.0 x 39.0 x 27.0 cm Berat: 6 kg SKU: 10467931 Nama Komoditas: KELS MINI STEAM OVEN TOASTER 10L WHITE',
            'put_on_highlight' => true,
            'views' => rand(100, 10000),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        $productType = ProductType::create([
            'product_id' => $product->id,
            'name' => 'Putih',
            'sku' => 'ol-10566312',
            'description' => 'Sharp Air Conditioner 1 Pk Ah/au-xp10yhy warna putih',
            'price' => 2000000,
            'color' => '#ffffff',
            'discount_percentage' => 20,
            'discount_fixed' => 100000,
            'stock' => 100,
            'weight' => 20.04,
            'total_sales' => 250,
            'created_by' => $adminId,
            'updated_by' => $adminId,

        ]);


        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10476870_1.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10476870_2.jpg')->toMediaCollection('product-types');

        $productType->addMediaFromUrl('https://cdn.ruparupa.io/ruparupa-com/image/upload/Products/10476870_3.jpg')->toMediaCollection('product-types');
    }
}