<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();

        $user->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    // Add more tests as needed...
}