<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Upload;
use App\Models\ScheduleItem;
use Illuminate\Support\Facades\DB;

class ScheduleItemSeeder extends Seeder
{
    public function run()
    {
        $advertiserIds = Upload::distinct()->pluck('advertiser_id');
        $scheduleIdsMap = $advertiserIds->mapWithKeys(function ($id) {
            return [$id => DB::table('schedules')->inRandomOrder()->first()->id];
        });

        $uploads = Upload::all();
        foreach ($uploads as $upload) {
            ScheduleItem::factory()->create([
                'schedule_id' => $scheduleIdsMap[$upload->advertiser_id],
                'upload_id' => $upload->id,
                'advertiser_id' => $upload->advertiser_id,
                'file' => $upload->resource_path . $upload->resource_filename,
                'created_by' => DB::table('users')->inRandomOrder()->first()->id,
            ]);
        }
    }
}