<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get all user ids
        $userIds = DB::table('users')->pluck('id')->toArray();

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'created_by' => $this->faker->randomElement($userIds),
        ];
    }
}