<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UploadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get all user ids
        $userIds = DB::table('users')->pluck('id')->toArray();

        foreach (range(1,20) as $index) {
            // Generate a random resource type
            $resource_type = $faker->randomElement(['ban', 'btn', 'vid']);

            // Generate a random file name based on the resource type
            $resource_filename = $faker->lexify('???????????????') . '.' . ($resource_type == 'vid' ? 'mp4' : 'png');

            // Determine the file path based on the resource type
            $resource_path = 'storage/uploads/' . ($resource_type == 'vid' ? 'mp4' : ($resource_type == 'ban' ? 'banner' : 'button')) . '/';

            // Pick a random user id
            $uploaded_by = $faker->randomElement($userIds);

            // Determine if the file is uploaded
            $is_uploaded = $faker->boolean;

            // Determine the uploaded_at timestamp
            $uploaded_at = $is_uploaded ? $faker->dateTimeThisYear : null;

            DB::table('uploads')->insert([
                'resource_type' => $resource_type,
                'resource_filename' => $resource_filename,
                'resource_path' => $resource_path,
                'is_uploaded' => $is_uploaded,
                'uploaded_by' => $uploaded_by,
                'uploaded_at' => $uploaded_at,
                'notes' => $faker->paragraph,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
