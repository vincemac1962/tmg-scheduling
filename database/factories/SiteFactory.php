<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'site_ref' => $this->faker->unique()->randomNumber(5),
            'site_name' => $this->faker->company,
            'site_address' => $this->faker->address,
            'site_postcode' => $this->faker->postcode,
            'site_country' => $this->faker->country,
            'site_contact' => $this->faker->name,
            'site_email' => $this->faker->unique()->safeEmail,
            'site_active' => $this->faker->boolean,
            'site_last_updated' => $this->faker->dateTimeThisYear,
            'site_notes' => $this->faker->sentence,
        ];
    }
}
