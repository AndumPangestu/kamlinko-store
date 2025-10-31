<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $hasDefaultAddress = $user->userAddress()->where('is_default', true);
        return [
            'user_id' => $user->id,
            'receiver_name' => fake()->name(),
            'address' => fake()->address(),
            'city' => null, // Placeholder, will be set in the seeder
            'province' => null, // Placeholder, will be set in the seeder
            'subdistrict' => fake()->city(),
            'zip' => fake()->postcode(),
            'latitude' => fake()->randomFloat(6,  -6.55, -6.60), // Indonesia Latitude
            'longitude' => fake()->randomFloat(6, 106, 107), // Indonesia Longitude
            'description' => fake()->sentence(),
            'is_default' => true,
        ];
    }
}
