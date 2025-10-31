<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $imageBanner = [
            [
                'name' => 'Promo Diskon Semen',
                'description' => 'Diskon hingga 20% untuk semua jenis semen',
                'type' => 'image',
                'created_at' => now(),
                'updated_at' => now(),
                'url' => 'https://img.freepik.com/free-photo/people-renovating-house-concept_53876-24843.jpg?t=st=1733233653~exp=1733237253~hmac=36f983d4b665690b3992976875a7ce51ac7d8636d5a703f8a2b716c71fbbb0ab&w=996'
            ],
            [
                'name' => 'Promo Alat Bangunan',
                'description' => 'Diskon besar untuk berbagai alat bangunan',
                'type' => 'image',
                'created_at' => now(),
                'updated_at' => now(),
                'url' => 'https://img.freepik.com/free-photo/diy-tools_144627-32164.jpg?t=st=1733233714~exp=1733237314~hmac=aec2bc62bd687ef13ff43fcaccdc1ac13d3cc307c9d1ec8534a71cb66ebed271&w=996'
            ]
        ];

        $youtubeBanner = [
            [
                'name' => 'The Power and Beauty of Construction Sites',
                'description' => 'The Power and Beauty of Construction Sites',
                'type' => 'youtube',
                'created_at' => now(),
                'updated_at' => now(),
                'url' => 'https://www.youtube.com/watch?v=4BzjUq921Y4&ab_channel=TheRoboCollective'
            ]
        ];

        $adminId = User::where('role', 'admin')->first()->id;
        foreach ($imageBanner as $banner) {
            $item = Banner::create([
                'name' => $banner['name'],
                'description' => $banner['description'],
                'type' => $banner['type'],
                'created_at' => $banner['created_at'],
                'updated_at' => $banner['updated_at'],
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]);

            $item->addMediaFromUrl($banner['url'])->toMediaCollection('banners');
        }

        foreach ($youtubeBanner as $banner) {
            $item = Banner::create([
                'name' => $banner['name'],
                'description' => $banner['description'],
                'type' => $banner['type'],
                'created_at' => $banner['created_at'],
                'updated_at' => $banner['updated_at'],
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]);

            $youtubeUrl = $banner['url'];
            parse_str(parse_url($youtubeUrl, PHP_URL_QUERY), $urlParams);
            $videoId = $urlParams['v'] ?? null;
            $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/0.jpg";
            $item->addMediaFromUrl($thumbnailUrl)
                ->withCustomProperties(['url' => $youtubeUrl])
                ->toMediaCollection('banners/yt');
        }
    }
}
