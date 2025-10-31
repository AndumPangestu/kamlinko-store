<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Admin
        // $admin = User::create([
        //     'name' => 'Admin',
        //     'username' => 'sahcadmin',
        //     'email' => 'admin@test.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'admin',
        //     'phone' => '1234567890',
        // ]);

        // $admin->addMediaFromUrl('https://png.pngtree.com/png-vector/20240601/ourmid/pngtree-casual-man-flat-design-avatar-profile-picture-vector-png-image_12593008.png')->preservingOriginal()
        //     ->toMediaCollection('user/profile_picture');

        // // Super Admin
        // $admin = User::create([
        //     'name' => 'Super',
        //     'username' => 'sahcsuper',
        //     'email' => 'super@test.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'superadmin',
        //     'phone' => '12345678901',
        // ]);
        // $admin->addMediaFromUrl('https://png.pngtree.com/png-vector/20240601/ourmid/pngtree-casual-man-flat-design-avatar-profile-picture-vector-png-image_12593008.png')->preservingOriginal()
        //     ->toMediaCollection('user/profile_picture');

        // Super Admin
        $admin = User::create([
            'name' => 'Temp Super',
            'username' => 'tempsuper',
            'email' => 'super@temp.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'phone' => '62898765432',
        ]);
        $admin->addMediaFromUrl('https://png.pngtree.com/png-vector/20240601/ourmid/pngtree-casual-man-flat-design-avatar-profile-picture-vector-png-image_12593008.png')->preservingOriginal()
            ->toMediaCollection('user/profile_picture');
    }
}
