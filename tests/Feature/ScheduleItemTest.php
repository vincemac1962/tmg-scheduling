<?php

namespace Tests\Feature;

use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker;

class ScheduleItemTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        // Arrange
        $faker = Faker\Factory::create();

        // Generate a random resource type
        $resource_type = $faker->randomElement(['ban', 'btn', 'vid']);

        // Determine the file path based on the resource type
        $resource_path = 'storage/uploads/' . ($resource_type == 'vid' ? 'mp4' : ($resource_type == 'ban' ? 'banner' : 'button')) . '/';

        // Generate a random file name based on the resource type
        $resource_filename = $faker->lexify('???????????????') . '.' . ($resource_type == 'vid' ? 'mp4' : 'png');

        $upload = Upload::factory()->create([
            'resource_type' => $resource_type,
            'resource_filename' => $resource_filename,
            'resource_path' => $resource_path,
        ]);

        $filePath = $upload->resource_path . $upload->resource_filename;

        // Create a User instance
        $user = User::factory()->create();

        // Create a Schedule instance
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);

        $scheduleItems = ScheduleItem::factory()->count(3)->create([
            'file' => $filePath,
            'schedule_id' => $schedule->id, // Use the id of the Schedule instance
        ]);

        // Act
        $response = $this->get('/schedule_items');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('scheduleItems');

        // Get the ScheduleItem instances returned by the paginator
        $returnedScheduleItems = $response->viewData('scheduleItems');

        // Compare the IDs of the ScheduleItem instances in the collections
        $this->assertEquals($scheduleItems->pluck('id')->toArray(), $returnedScheduleItems->pluck('id')->toArray());
    }

    public function testShowMethod()
    {
        // Arrange
        $faker = Faker\Factory::create();

        // Generate a random resource type
        $resource_type = $faker->randomElement(['ban', 'btn', 'vid']);

        // Determine the file path based on the resource type
        $resource_path = 'storage/uploads/' . ($resource_type == 'vid' ? 'mp4' : ($resource_type == 'ban' ? 'banner' : 'button')) . '/';

        // Generate a random file name based on the resource type
        $resource_filename = $faker->lexify('???????????????') . '.' . ($resource_type == 'vid' ? 'mp4' : 'png');

        $upload = Upload::factory()->create([
            'resource_type' => $resource_type,
            'resource_filename' => $resource_filename,
            'resource_path' => $resource_path,
        ]);

        $filePath = $upload->resource_path . $upload->resource_filename;

        // Create a User instance
        $user = User::factory()->create();

        // Create a Schedule instance
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);

        $scheduleItem = ScheduleItem::factory()->create([
            'file' => $filePath,
            'schedule_id' => $schedule->id, // Use the id of the Schedule instance
        ]);

        // Act
        $response = $this->get('/schedule_items/' . $scheduleItem->id);

        // Assert
        $response->assertStatus(200);
    }



}
