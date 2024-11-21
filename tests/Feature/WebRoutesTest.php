<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    // prevent data from being deleted after each test
    use DatabaseTransactions;

    public function test_index()
    {
        // Create a new user instance
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Send a GET request to the /sites route
        $response = $this->get('/');

        // Assert that the response status is 200
        $response->assertStatus(200);
    }
    // Add more tests for other routes here, following the same pattern
}