<?php

namespace Database\Factories;

use App\Models\Advertiser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $resource_type = $this->faker->randomElement(['ban', 'btn', 'mp4']);
        $resource_filename = $this->faker->lexify('???????????????') . '.' . ($resource_type == 'vid' ? 'mp4' : 'png');
        $file_path = 'storage/uploads/' . ($resource_type == 'mp4' ? 'mp4' : ($resource_type == 'ban' ? 'banner' : 'button')) . '/';
        $is_uploaded = $this->faker->boolean;
        $uploaded_at = $is_uploaded ? $this->faker->dateTimeThisYear : null;
        // Check if any advertisers exist, if not, create one
        $advertiser_id = Advertiser::query()->exists() ? Advertiser::all()->random()->id : Advertiser::factory()->create()->id;

        return [
            'advertiser_id' => $advertiser_id, // Assign the random advertiser ID
            'resource_type' => $resource_type,
            'resource_filename' => $resource_filename,
            'resource_path' => $file_path,
            'is_uploaded' => $is_uploaded,
            'uploaded_by' => User::inRandomOrder()->first()->id,
            'uploaded_at' => $uploaded_at,
        ];
    }
}