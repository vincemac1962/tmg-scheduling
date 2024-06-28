<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiteTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'site_type' => $this->faker->word,
            'site_prefix' => strtoupper($this->faker->lexify('??')),
        ];
    }
}
