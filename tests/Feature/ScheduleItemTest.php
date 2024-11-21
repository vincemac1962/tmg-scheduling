<?php /** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

/** @noinspection ALL */

namespace Tests\Feature;

use App\Models\Advertiser;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Upload;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Faker;

class ScheduleItemTest extends TestCase
{
    //use DatabaseTransactions;
    use RefreshDatabase;

    public function testIndexMethod()
    {
        // Arrange
        $this->withoutExceptionHandling(); // Temporarily added to get more detailed error messages
        $user = User::factory()->create();
        $upload = null;
        try {
            $upload = Upload::factory()->create(['uploaded_by' => $user->id]);
        } catch (Exception $e) {
            Log::error("Failed to create upload: " . $e->getMessage());
            // Optionally, rethrow the exception or handle it as needed
        }
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);
        $scheduleItems = ScheduleItem::factory()->count(3)->create([
            'file' => $upload->resource_path . $upload->resource_filename,
            'schedule_id' => $schedule->id,
            'upload_id' => $upload->id,
            'created_by' => $user->id
        ]);

        // Act
        $response = $this->get('/schedule_items');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('scheduleItems');
        $returnedScheduleItems = $response->viewData('scheduleItems');
        $this->assertEquals(3, $returnedScheduleItems->count()); // Ensure 3 items are returned
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

        $advertiser = Advertiser::factory()->create();

        $upload = null;

        try {
            $upload = Upload::factory()->create();
        } catch (Exception $e) {
            Log::error("Failed to create upload: " . $e->getMessage());
            // Optionally, rethrow the exception or handle it as needed
        }


        $filePath = $upload->resource_path . $upload->resource_filename;

        // Create a User instance
        $user = User::factory()->create();

        // Create a Schedule instance
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);

        $scheduleItem = ScheduleItem::factory()->create([
            'file' => $filePath,
            'schedule_id' => $schedule->id, // Use the id of the Schedule instance
            'upload_id' => $upload->id,
            'created_by' => $user->id
        ]);

        // Act
        $response = $this->get('/schedule_items/' . $scheduleItem->id);

        // Assert
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_a_schedule_item()
    {
        // Arrange
        Storage::fake('public');

        // Create a User instance
        $user = User::factory()->create();

        $this->actingAs($user);
        $file = UploadedFile::fake()->create('video.mp4', 1000, 'video/mp4');

        // Create a Schedule instance
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);

        $data = [
            'resource_filename' => $file,
            'title' => 'Test Title',
            'start_date' => '2023-01-01 00:00:00',
            'end_date' => '2023-01-31 00:00:00',
            'created_by' => $user->id, // Use the id of the User instance
            'resource_type' => 'mp4',
            'schedule_id' => $schedule->id // Use the id of the Schedule instance
        ];

        // Act
        $response = $this->post(route('schedule_items.store'), $data);



        // Assert
        $response->assertRedirect(route('schedules.index'));
        $response->assertSessionHas('success', 'Schedule item created successfully.');
        $this->assertDatabaseHas('uploads', [
            'resource_filename' => $file->getClientOriginalName(),
            'resource_type' => 'mp4',
            'uploaded_by' => $user->id,
        ]);
        $this->assertDatabaseHas('schedule_items', [
            'title' => 'Test Title',
            'start_date' => '2023-01-01 00:00:00',
            'end_date' => '2023-01-31 00:00:00',
            'file' => 'storage/uploads/mp4/video.mp4',
            'created_by' => $user->id,
            'schedule_id' => $schedule->id
        ]);
    }

    // test the destroy method
    public function testDestroyMethod()
    {
        // Arrange
        $this->withoutExceptionHandling(); // Temporarily added to get more detailed error messages
        $user = User::factory()->create();
        $upload = null;
        try {
            $upload = Upload::factory()->create(['uploaded_by' => $user->id]);
        } catch (Exception $e) {
            Log::error("Failed to create upload: " . $e->getMessage());
            // Optionally, rethrow the exception or handle it as needed
        }
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);
        $scheduleItem = ScheduleItem::factory()->create([
            'file' => $upload->resource_path . $upload->resource_filename,
            'schedule_id' => $schedule->id,
            'upload_id' => $upload->id,
            'created_by' => $user->id
        ]);

        // Act
        $response = $this->delete('/schedule_items/' . $scheduleItem->id);
        // Assert
        $response->assertStatus(302);
        // Ensure you're passing the required `schedule` parameter to the route
        $response->assertRedirect('/schedules/' . $schedule->id);
    }



}
