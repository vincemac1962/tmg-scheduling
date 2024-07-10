<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertiser>
 */
class AdvertiserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            // 'resource_type' => $resource_type,
            'contract' => $this->faker->unique()->numberBetween(100000, 999999) . $this->faker->randomElement(['Y1', 'Y2']),
            'business_name' => $this->faker->company,
            'address_1' => $this->faker->streetAddress,
            'address_2' => $this->faker->streetAddress,
            'street' => $this->faker->streetName,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'phone' => $this->faker->phoneNumber,
            'mobile' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'url' => $this->faker->url,
            'social' => $this->faker->url,
            'banner' => substr(Str::slug($this->faker->company), 0, 16) . '.png',
            'button' => substr(Str::slug($this->faker->company), 0, 16) . '.png',
            'mp4' => substr(Str::slug($this->faker->company), 0, 16) . '.mp4',
            'created_by' => User::query()->exists() ? User::all()->random()->id : User::factory()->create()->id,
        ];
    }
}
