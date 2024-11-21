<?php /** @noinspection PhpUndefinedMethodInspection */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'email' => 'vince.macrae@gmail.com', // Check if this email exists
        ], [
            'name' => 'Vincent MacRae',
            'email' => 'vince.macrae@gmail.com',
            'password' => Hash::make('T1m3M3d1a'), // Hash the password
        ]);

        $faker = Faker::create();

        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach (range(1,10) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => Hash::make('secret'),
            ]);
        }
    }
}