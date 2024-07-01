<?php

namespace Database\Factories;

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
        $resource_type = $this->faker->randomElement(['ban', 'btn', 'vid']);
        $resource_filename = $this->faker->lexify('???????????????') . '.' . ($resource_type == 'vid' ? 'mp4' : 'png');
        $file_path = 'storage/uploads/' . ($resource_type == 'vid' ? 'mp4' : ($resource_type == 'ban' ? 'banner' : 'button')) . '/';
        $is_uploaded = $this->faker->boolean;
        $uploaded_at = $is_uploaded ? $this->faker->dateTimeThisYear : null;

        return [
            'resource_type' => $resource_type,
            'resource_filename' => $resource_filename,
            'file_path' => $file_path,
            'is_uploaded' => $is_uploaded,
            'uploaded_by' => $this->faker->numberBetween(1,5),
            'uploaded_at' => $uploaded_at,
            'notes' => $this->faker->sentence,
        ];
    }
}