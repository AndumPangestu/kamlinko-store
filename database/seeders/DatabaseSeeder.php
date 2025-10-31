<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Brand;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductCategory;
use App\Models\Voucher;
use App\Models\Transaction;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Notifications\Customer\PaymentReminderNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Load city and province list once
        $provinces = [];
        $citiesByProvince = [];
        $subdistrictsByCity = [];
        if (($open = fopen(storage_path('app/public/wilayah.csv'), "r")) !== false) {
            while (($data = fgetcsv($open, 1000, ",")) !== false) {
                $current = explode(',', trim($data[0]));
                $code = $current[0];
                $name = $current[1];
                if (strlen($code) === 2) {
                    $provinces[] = [
                        'id' => $code,
                        'name' => $name
                    ];
                } elseif (strlen($code) === 5) {
                    $provinceCode = substr($code, 0, 2);
                    $citiesByProvince[$provinceCode][] = [
                        'id' => $code,
                        'name' => $name
                    ];
                } elseif (strlen($code) === 8) {
                    $cityCode = substr($code, 0, 5);
                    $subdistrictsByCity[$cityCode][] = [
                        'id' => $code,
                        'name' => $name
                    ];
                }
            }
            fclose($open);
        }
        // for Customer, UserAddress factory also creates user
        UserAddress::factory()->count(20)->state(new Sequence(function () use ($provinces, $citiesByProvince, $subdistrictsByCity) {
            $randomProvince = fake()->randomElement($provinces);
            $provinceId = $randomProvince['id'];
            $provinceName = $randomProvince['name'];
            $randomCity = fake()->randomElement($citiesByProvince[$provinceId]);
            $randomCityName = $randomCity['name'];
            $randomCityId = $randomCity['id'];
            $randomSubdistrict = fake()->randomElement($subdistrictsByCity[$randomCityId])['name'];
            return ['province' => $provinceName, 'city' => $randomCityName, 'subdistrict' => $randomSubdistrict];
        }))->create();



        $this->call(ProductCategorySeeder::class);
        $this->call(ProductTagSeeder::class);

        $this->call(BrandSeeder::class);
        $this->call(ProductSeeder::class);

        $this->call(VoucherSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(BannerSeeder::class);
    }
}
