<?php

namespace Tests\Feature;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Schedule;

class SchedulesTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    // test index method
    public function testIndex(): void
    {
        $response = $this->get('/schedules');
        $response->assertStatus(200);
    }

    // test show method
    public function testShow(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->get('/schedules/' . $schedule->id);

        $response->assertStatus(200);
    }

    // test edit method
    public function testEdit(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->get('/schedules/' . $schedule->id . '/edit');

        $response->assertStatus(200);
    }

    // test update method
    public function testUpdate(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->put('/schedules/' . $schedule->id, [
            'title' => 'Test Title',
            'description' => 'Test Description',
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'file' => 'test.pdf',
            'created_by' => 1
        ]);

        $response->assertStatus(302);
    }

    // test store method
    public function testStore(): void
    {
        $schedule = Schedule::factory()->make();

        $response = $this->post('/schedules', [
            'title' => $schedule->title,
            'description' => $schedule->description,
            'start_date' => $schedule->start_date,
            'end_date' => $schedule->end_date,
            'file' => $schedule->file,
            'created_by' => $schedule->created_by
        ]);

        $response->assertStatus(302);
    }

    // test destroy method

    public function testDestroy(): void
    {
        $schedule = Schedule::factory()->create();

        $response = $this->delete('/schedules/' . $schedule->id);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
    }

    // test addSelectedAdvertisers method
    public function testAddSelectedAdvertisers(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        $schedule = Schedule::factory()->create();
        // creates session id variable
        session(['schedule_id' => $schedule->id]);

        $response = $this->post('/schedule/addSelectedAdvertisers', [
            'advertiser_ids' => [1, 2, 3]
        ]);

        $response->assertStatus(302);
    }

    public function testAssociateSites(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a schedule
        $schedule = Schedule::factory()->create();

        // Create some sites
        $sites = Site::factory()->count(3)->create();

        // Call the associateSites method
        $response = $this->post('/schedules/' . $schedule->id . '/associate-sites', [
            'sites' => $sites->pluck('id')->toArray()
        ]);

        // Assert the response status
        $response->assertStatus(302);

        // Assert the sites are associated with the schedule
        $this->assertEquals($sites->pluck('id')->toArray(), $schedule->sites->pluck('id')->toArray());
    }

    // show associated sites
    public function testShowAssociatedSites(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a schedule
        $schedule = Schedule::factory()->create();

        // Set the session variable for schedule_id
        session(['schedule_id' => $schedule->id]);

        // Create some sites
        $sites = Site::factory()->count(3)->create();

        // Associate the sites with the schedule
        $schedule->sites()->sync($sites->pluck('id')->toArray());

        // Call the showAssociatedSites method
        $response = $this->get('/schedules/' . $schedule->id . '/associated-sites');

        // Assert the response status
        $response->assertStatus(200);

        // Assert the sites are displayed
        $response->assertSee($sites->first()->site_name);
        $response->assertSee($sites->last()->site_name);

        // Ensure the route call includes the schedule parameter
        $response = $this->delete(route('schedules.removeSite', ['schedule' => $schedule->id, 'site' => $sites->first()->id]));

        // Assert the response status
        $response->assertStatus(302);

        // Assert the site is removed from the schedule
        $this->assertDatabaseMissing('schedule_site', [
            'schedule_id' => $schedule->id,
            'site_id' => $sites->first()->id
        ]);
    }


}
