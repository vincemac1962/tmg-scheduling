<?php

namespace Database\Factories;

use App\Models\Advertiser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends Factory<Advertiser>
 */
class AdvertiserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $contract = $this->faker->unique()->numerify('######Y#'); // Example pattern

        return [
            'contract' => $contract,
            'business_name' => $this->faker->company,
            'address_1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'county' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country' => $this->faker->country,
            'phone' => $this->faker->phoneNumber,
            'mobile' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'url' => $this->faker->url,
            'social' => $this->faker->url,
            'banner' => 'ban-' . $contract . '.png',
            'button' => 'btn-' . $contract . '.png',
            'mp4' => 'mp4-' . $contract . '.mp4',
            'sort_order' => $this->faker->numberBetween(1, 100),
            'is_active' => $this->faker->boolean,
            'is_deleted' => false,
            'created_by' => User::inRandomOrder()->firstOrCreate([
                // Define default attributes for the User if none exists
                'name' => 'Default User',
                'email' => 'defaultuser' . rand(1, 10000) . '@example.com', // Generate a unique email
                'password' => bcrypt('password'),
            ])->id,
        ];
    }
}
