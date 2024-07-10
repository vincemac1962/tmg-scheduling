<?php

namespace Database\Factories;

use App\Models\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleItem>
 */
class ScheduleItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduleItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get all schedule ids
        $scheduleIds = DB::table('schedules')->pluck('id')->toArray();

        // Get all schedule ids
        $uploadIds = DB::table('uploads')->pluck('id')->toArray();

        // Get all user ids
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Get a random file from the uploads table
        $file = DB::table('uploads')->inRandomOrder()->limit(1)->first();
        $filePath = $file->resource_path . $file->resource_filename;

        // Generate random start and end dates
        $startDate = $this->faker->dateTimeBetween('now', '+12 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+12 months');

        return [
            'schedule_id' => $this->faker->randomElement($scheduleIds),
            'upload_id' => $this->faker->randomElement($uploadIds),
            'advertiser_id' => null,
            'title' => $this->faker->sentence,
            'description' => substr($this->faker->paragraph($nbSentences = 3, $variableNbSentences = true), 0, 255),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'file' => $filePath,
            'created_by' => $this->faker->randomElement($userIds),
        ];
    }
}
