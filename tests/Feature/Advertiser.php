<?php /** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpUndefinedMethodInspection */

namespace Tests\Feature;

use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class Advertiser extends TestCase
{
    use RefreshDatabase;

    // test index method
    public function testIndex(): void
    {
        $response = $this->get('/advertisers');
        $response->assertStatus(200);
    }

    /** @test */
    public function an_advertiser_can_be_created()
    {
        $this->withoutExceptionHandling();

        // Assuming you have a user who can create an advertiser
        $user = User::factory()->create();

        // Simulate being logged in as that user
        $this->actingAs($user);

        // Create a Schedule instance
        $schedule = Schedule::factory()->create();

        // Simulate file uploads
        $bannerFile = UploadedFile::fake()->image('banner.png');
        $buttonFile = UploadedFile::fake()->image('button.png');
        $mp4File = UploadedFile::fake()->create('video.mp4', 1000); // Size in kilobytes

        // Prepare the data for the new advertiser with the simulated file uploads
        $advertiserData = [
            'business_name' => 'Test Business',
            'contract' => '12345',
            'banner' => $bannerFile,
            'button' => $buttonFile,
            'mp4' => $mp4File,
            'schedule_id' => $schedule->id,
        ];

        // Make a POST request to the route handling the store() method and pass the data
        $response = $this->post(route('advertisers.store'), $advertiserData);

        // Assert the response is a redirect to the expected route (adjust as necessary)
        //$response->assertRedirect(route('schedules.show', ['schedule' => $schedule->id]));
        $response->assertRedirect(route('advertisers.index'));

        // Optionally, assert a session flash message exists
        $response->assertSessionHas('success', 'Advertiser created successfully.');

        // Assert the advertiser was created in the database
        $this->assertDatabaseHas('advertisers', [
            'business_name' => 'Test Business',
            'contract' => '12345',
        ]);
    }

    public function an_advertiser_can_be_created_without_a_schedule()
    {
        $this->withoutExceptionHandling();

        // Assuming you have a user who can create an advertiser
        $user = User::factory()->create();

        // Simulate being logged in as that user
        $this->actingAs($user);

        // Create a Schedule instance
        //$schedule = Schedule::factory()->create();

        // Simulate file uploads
        $bannerFile = UploadedFile::fake()->image('banner.png');
        $buttonFile = UploadedFile::fake()->image('button.png');
        $mp4File = UploadedFile::fake()->create('video.mp4', 1000); // Size in kilobytes

        // Prepare the data for the new advertiser with the simulated file uploads
        $advertiserData = [
            'business_name' => 'Test Business',
            'contract' => '12345',
            'banner' => $bannerFile,
            'button' => $buttonFile,
            'mp4' => $mp4File,
            //'schedule_id' => $schedule->id,
        ];

        // Make a POST request to the route handling the store() method and pass the data
        $response = $this->post(route('advertisers.store'), $advertiserData);

        // Assert the response is a redirect to the expected route (adjust as necessary)
        //$response->assertRedirect(route('schedules.show', ['schedule' => $schedule->id]));
        $response->assertRedirect(route('advertisers.index'));

        // Optionally, assert a session flash message exists
        $response->assertSessionHas('success', 'Advertiser created successfully.');

        // Assert the advertiser was created in the database
        $this->assertDatabaseHas('advertisers', [
            'business_name' => 'Test Business',
            'contract' => '12345',
        ]);
    }

    /** @test */
    public function an_advertiser_can_be_updated()
    {
        $this->withoutExceptionHandling();

        // Create a user with permissions to update an advertiser
        $user = User::factory()->create();

        // Simulate being logged in as that user
        $this->actingAs($user);

        // Simulate file uploads
        $bannerFile = UploadedFile::fake()->image('banner.png');
        $buttonFile = UploadedFile::fake()->image('button.png');
        $mp4File = UploadedFile::fake()->create('video.mp4', 1000); // Size in kilobytes

        // Create an existing advertiser and a schedule for prerequisites
        $existingAdvertiser = \App\Models\Advertiser::factory()->create([
            'business_name' => 'Original Business',
            'contract' => '1234',
            'banner' => $bannerFile,
            'button' => $buttonFile,
            'mp4' => $mp4File,
        ]);


        // Prepare the data for updating the advertiser
        $updateData = [
            'business_name' => 'Updated Business',
            'contract' => '12345',
            'banner' => $bannerFile,
            'button' => $buttonFile,
            'mp4' => $mp4File,
        ];

        // Make a PUT request to the route handling the update() method and pass the update data
        $response = $this->put(route('advertisers.update', ['advertiser' => $existingAdvertiser->id]), $updateData);

        // Assert the response is a redirect to the expected route (adjust as necessary)
        //Log::info('Attempting to redirect to route: ' . route('advertisers.show', ['advertiser' => $existingAdvertiser->id], false));
        //$response->assertRedirect(route('advertisers.show', ['advertiser' => $existingAdvertiser->id]));
        $response->assertRedirect();

        // Optionally, assert a session flash message exists
        $response->assertSessionHas('success', 'Advertiser updated successfully.');

        // Assert the advertiser was updated in the database
        $this->assertDatabaseHas('advertisers', [
            'id' => $existingAdvertiser->id,
            'business_name' => 'Updated Business',
        ]);
    }

    public function testShow(): void
    {
        $advertiser = \App\Models\Advertiser::factory()->create();

        $response = $this->get('/advertisers/' . $advertiser->id);

        $response->assertStatus(200);
    }

    public function testDestroy(): void
    {
        $advertiser = \App\Models\Advertiser::factory()->create();

        $response = $this->delete('/advertisers/' . $advertiser->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('advertisers', ['id' => $advertiser->id]);
    }
}