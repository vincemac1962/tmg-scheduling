<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SiteTypesTableSeeder;
use Database\Seeders\UploadsTableSeeder;
use Database\Seeders\UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            SiteTypesTableSeeder::class,
            SitesTableSeeder::class,
            ScheduleTableSeeder::class,
            AdvertiserSeeder::class,
            ScheduleItemSeeder::class,
        ]);


        // \App\Models\User::factory(10)->create();
    }
}
