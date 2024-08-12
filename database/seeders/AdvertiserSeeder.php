<?php

namespace Database\Seeders;

use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Advertiser;
use Faker\Factory as Faker;


class AdvertiserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param $faker
     * @return void
     */
    public function run()
    {
            // Retrieve all User IDs from the database
            $userIds = User::all()->pluck('id')->toArray();

            $faker = Faker::create();

            Advertiser::factory()->count(25)->create()->each(function ($advertiser) use ($faker, $userIds) {
            $uploads = [
                ['ban', $advertiser->banner, 'banners/'],
                ['btn', $advertiser->button, 'buttons/'],
                ['mp4', $advertiser->mp4, 'mp4s/']
            ];

            foreach ($uploads as $upload) {
                Upload::create([
                    'advertiser_id' => $advertiser->id,
                    'resource_type' => $upload[0],
                    'resource_filename' => $upload[1],
                    'resource_path' => $upload[2],
                    'uploaded_by' => $userIds[0],
                    'is_uploaded' => true,
                    'uploaded_at' => $faker->dateTimeThisYear,
                ]);
            }
        });
    }
}
