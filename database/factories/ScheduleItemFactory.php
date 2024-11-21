<?php

namespace Database\Factories;

use App\Models\ScheduleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScheduleItem>
 */
class ScheduleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+12 months');

        return [
            'title' => $this->faker->sentence,
            'description' => substr($this->faker->paragraph(), 0, 255),
            'start_date' => $startDate,
            'end_date' => $this->faker->dateTimeBetween($startDate, '+12 months'),
            // 'schedule_id', 'upload_id', 'advertiser_id', 'file', 'uploaded_by' will be assigned in the seeder
        ];
    }
}
