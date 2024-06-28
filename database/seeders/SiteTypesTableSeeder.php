<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // remove all other records from site_types table
        DB::table('site_types')->truncate();
        // insert site types
        DB::table('site_types')->insert([
            ['site_type' => 'Builders Merchant', 'site_prefix' => 'BM'],
            ['site_type' => 'Realtor', 'site_prefix' => 'EA'],
            ['site_type' => 'Theatre', 'site_prefix' => 'TH'],
            ['site_type' => 'Retirement Community', 'site_prefix' => 'RC']
            // Add more site types as needed
        ]);
    }
}
