<?php

namespace Database\Seeders;

use App\Models\ScheduleItem;
use Illuminate\Database\Seeder;

class ScheduleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScheduleItem::factory()->count(5)->create();
    }
}
