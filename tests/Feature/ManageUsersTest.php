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
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get('/profile/1/edit')
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch('/profile/1')
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get("/profile/{$user->id}")
            ->assertOk();

        $this->actingAs($user)
            ->get("/profile/{$user->id}/edit")
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

        $this->actingAs($users[0])
            ->get("/profile/{$users[1]->id}/edit")
            ->assertStatus(302)
            ->assertRedirect('dashboard')
            ->assertSessionHas('message', 'You can only edit your own profile.');
    }

    /** @test */
    public function user_can_update_own_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch("/profile/{$user->id}", ['name' => 'John Doe'])
            ->assertStatus(302)
            ->assertRedirect(route('users.show', ['user' => $user]))
            ->assertSessionHas('message', 'Profile updated.');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    public function update_profile_name_field_required()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch("/profile/{$user->id}", ['name' => ''])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
    }
}
