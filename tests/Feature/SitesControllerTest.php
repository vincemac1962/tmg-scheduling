<?php /** @noinspection ALL */

namespace Tests\Feature;

use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitesControllerTest extends TestCase
{
    use RefreshDatabase;

    // ...

    /*public function test_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/sites');

        $response->assertStatus(200);
    } */

    public function test_index_with_filter_and_sort()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test filtering
        $response = $this->get('/sites?filter=AA');
        $response->assertStatus(200);
        $response->assertSee('Site A');
        $response->assertDontSee('Site B');
        $response->assertDontSee('Site C');

        // Test sorting
        $response = $this->get('/sites?sort_by=site_name&direction=desc');
        $response->assertStatus(200);

        // Fetch the sites from the response
        $sites = $response->viewData('sites');

        // Check the order of the sites
        $this->assertEquals('Site C', $sites[0]->site_name);
        $this->assertEquals('Site B', $sites[1]->site_name);
        $this->assertEquals('Site A', $sites[2]->site_name);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $site = Site::factory()->create();

        $response = $this->get('/sites/' . $site->id);

        $response->assertStatus(200);
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/sites/create');

        $response->assertStatus(200);
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $formData = [
            'site_ref' => 'Test Site Ref',
            'site_name' => 'Test Site Name',
            'site_active' => 1
        ];

        $response = $this->post('/sites', $formData);

        $response->assertStatus(302);
        $response->assertRedirect('/sites');
        $this->assertDatabaseHas('sites', $formData);
    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $site = Site::factory()->create();

        $response = $this->get('/sites/' . $site->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewHas('site');
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $site = Site::factory()->create();

        $formData = [
            'site_ref' => 'Updated Site Ref',
            'site_name' => 'Updated Site Name',
            'site_active' => 1
        ];

        $response = $this->put('/sites/' . $site->id, $formData);

        $response->assertStatus(302);
        $response->assertRedirect('/sites');
        $this->assertDatabaseHas('sites', [
            'id' => $site->id,
            'site_ref' => 'Updated Site Ref',
            'site_name' => 'Updated Site Name',
            'site_active' => 1
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $site = Site::factory()->create();

        $response = $this->delete('/sites/' . $site->id);

        $response->assertStatus(302);
        $response->assertRedirect('/sites');
        $this->assertDatabaseMissing('sites', [
            'id' => $site->id
        ]);
    }
}