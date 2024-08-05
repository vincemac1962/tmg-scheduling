<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Upload;
use App\Models\Site;
use App\Models\User;

class GetSchedulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $user = User::factory()->create();

        // Create a site
        $site = Site::factory()->create();

        // Create a schedule
        $schedule = Schedule::factory()->create(['created_by' => $user->id]);

        // Associate schedule with site
        $site->schedules()->attach($schedule->id, ['downloaded' => false]);

        // Create an upload
        $upload = Upload::factory()->create();

        // Create schedule items
        ScheduleItem::factory()->create([
            'schedule_id' => $schedule->id,
            'upload_id' => $upload->id,
            'file' => 'somefile.txt',
            'created_by' => $user->id
        ]);
    }

    public function test_get_schedules()
    {
        $siteId = Site::first()->id;

        $response = $this->getJson("/api/schedules/{$siteId}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'schedule' => [
                        'id',
                        'title',
                        'created_at',
                        'updated_at'
                    ],
                    'items' => [
                        '*' => [
                            'id',
                            'schedule_id',
                            'advertiser_id',
                            'title',
                            'start_date',
                            'end_date',
                            'file',
                            'resource_type'
                        ]
                    ]
                ]
            ]);
    }
}
