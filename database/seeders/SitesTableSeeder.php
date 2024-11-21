<?php /** @noinspection PhpUnusedLocalVariableInspection */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get all site prefixes
        $sitePrefixes = DB::table('site_types')->pluck('site_prefix')->toArray();

        foreach (range(1,30) as $index) {
            // Pick a random site prefix
            $sitePrefix = $faker->randomElement($sitePrefixes);

            // Generate a random four-digit number
            $randomNumber = $faker->numberBetween(1000, 9999);

            // Combine the site prefix and the random number to create the site_ref
            $siteRef = $sitePrefix . $randomNumber;

            DB::table('sites')->insert([
                'site_ref' => $siteRef,
                'site_name' => $faker->company,
                'site_address' => $faker->address,
                'site_postcode' => $faker->postcode,
                'site_country' => $faker->country,
                'site_contact' => $faker->name,
                'site_email' => $faker->companyEmail,
                'site_active' => $faker->boolean,
                'site_notes' => $faker->sentence,
                'site_last_updated' => $faker->dateTimeThisYear,
            ]);
        }
    }
}