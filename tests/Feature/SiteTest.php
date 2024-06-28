<?php

namespace Tests\Feature;

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_site_can_be_created()
    {
        $site = Site::factory()->create();

        // Assert that the new Site exists in the database
        // Replace 'column_name' with the actual column names in your Site model
        $this->assertDatabaseHas('sites', [
            'site_ref' => $site->site_ref,
            'site_name' => $site->site_name,
            'site_address' => $site->site_address,
            'site_postcode' => $site->site_postcode,
            'site_country' => $site->site_country,
            'site_contact' => $site->site_contact,
            'site_email' => $site->site_email,
            'site_active' => $site->site_active,
            'site_last_updated' => $site->site_last_updated,
            'site_notes' => $site->site_notes,
        ]);
    }
}