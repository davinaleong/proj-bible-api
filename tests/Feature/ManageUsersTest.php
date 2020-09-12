<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ManageUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_access_endpoints()
    {
        $this->get('/profile/1')
            ->assertStatus(302);
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get("/profile/{$user->id}")
            ->assertOk();
    }

    /** @test */
    public function user_cannot_access_endpoints_if_not_the_same_user()
    {
        $users = User::factory(2)->create();

        $this->actingAs($users[0])
            ->get("/profile/{$users[1]->id}")
            ->assertStatus(302)
            ->assertRedirect('dashboard')
            ->assertSessionHas('message', 'You can only view your own profile.');
    }
}
