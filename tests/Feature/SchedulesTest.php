<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

}
