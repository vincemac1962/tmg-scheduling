<?php /** @noinspection ALL */

/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\Upload;
use App\Models\Site;
use App\Models\User;
use App\Models\Advertiser;

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

        // Create an advertiser
        $advertiser = Advertiser::factory()->create();

        // Create schedule items
        ScheduleItem::factory()->create([
            'schedule_id' => $schedule->id,
            'upload_id' => $upload->id,
            'advertiser_id' => $advertiser->id,
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
                    ],
                    'advertisers' => [
                        '*' => [
                            'id',
                            'contract',
                            'business_name',
                            'address_1',
                            'address_2',
                            'street',
                            'city',
                            'county',
                            'postal_code',
                            'country',
                            'phone',
                            'mobile',
                            'email',
                            'url',
                            'social',
                            'banner',
                            'button',
                            'mp4'
                        ]
                    ]
                ]
            ]);
    }
}
