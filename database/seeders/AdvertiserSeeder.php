<?php

namespace Database\Seeders;

use App\Models\Advertiser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertiserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Advertiser::factory()->count(25)->create();
    }
}
