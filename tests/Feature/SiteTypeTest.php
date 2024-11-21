<?php

namespace Tests\Feature;

use App\Models\SiteType;
use Tests\TestCase;

class SiteTypeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_create_a_site_type()
    {
        $siteType = SiteType::factory()->create();

        $this->assertDatabaseHas('site_types', ['id' => $siteType->id]);
    }

    public function test_it_can_update_a_site_type()
    {
        $siteType = SiteType::factory()->create();

        $siteType->update(['site_type' => 'Updated Site Type']);

        $this->assertDatabaseHas('site_types', ['id' => $siteType->id, 'site_type' => 'Updated Site Type']);
    }
}
