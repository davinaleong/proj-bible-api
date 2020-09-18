<?php

namespace Tests\Feature;

use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
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

        $this->patch('/profile/1/change-password')
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

        $this->assertDatabaseHas('logs', [
           'user_id' => $user->id,
           'source' => Log::$TABLE_USERS,
           'source_id' => $user->id,
           'message' => 'User updated profile.'
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

    /** @test */
    public function user_can_change_password()
    {
        $old_password = 'password';
        $new_password = 'new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => $new_password,
                'confirm_new_password' => $new_password
            ])
            ->assertStatus(302)
            ->assertRedirect(route('users.show', ['user' => $user]))
            ->assertSessionHas([
                'message' => 'Password changed.'
            ]);
    }

    /** @test */
    public function change_password_password_field_is_required()
    {
        $old_password = 'password';
        $new_password = 'new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => '',
                'new_password' => $new_password,
                'confirm_new_password' => $new_password
            ])
            ->assertSessionHasErrors([
                'password' => 'The password field is required.'
            ]);
    }

    /** @test */
    public function change_password_password_field_must_be_at_least_8_characters_long()
    {
        $old_password = 'pass';
        $new_password = 'new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => $new_password,
                'confirm_new_password' => $new_password
            ])
            ->assertSessionHasErrors([
                'password' => 'The password must be at least 8 characters.'
            ]);
    }

    /** @test */
    public function change_password_new_password_field_is_required()
    {
        $old_password = 'password';
        $new_password = 'new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => '',
                'confirm_new_password' => $new_password
            ])
            ->assertSessionHasErrors([
                'new_password' => 'The new password field is required.'
            ]);
    }

    /** @test */
    public function change_password_new_password_field_must_be_at_least_8_characters_long()
    {
        $old_password = 'password';
        $new_password = 'new';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => $new_password,
                'confirm_new_password' => $new_password
            ])
            ->assertSessionHasErrors([
                'new_password' => 'The new password must be at least 8 characters.'
            ]);
    }

    /** @test */
    public function change_password_confirm_new_password_field_is_required()
    {
        $old_password = 'password';
        $new_password = 'new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => $new_password,
                'confirm_new_password' => ''
            ])
            ->assertSessionHasErrors([
                'confirm_new_password' => 'The confirm new password field is required.'
            ]);
    }

    /** @test */
    public function change_password_confirm_new_password_field_must_be_at_least_8_characters_long()
    {
        $old_password = 'password';
        $new_password = 'new password';
        $confirm_new_password = 'new';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => $new_password,
                'confirm_new_password' => $confirm_new_password
            ])
            ->assertSessionHasErrors([
                'confirm_new_password' => 'The confirm new password must be at least 8 characters.'
            ]);
    }

    /** @test */
    public function change_password_new_password_must_be_same_as_confirm_new_password()
    {
        $old_password = 'password';
        $new_password = 'new password';
        $confirm_new_password = 'confirm new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $old_password,
                'new_password' => $new_password,
                'confirm_new_password' => $confirm_new_password
            ])
            ->assertSessionHasErrors([
                'confirm_new_password' => 'The confirm new password and new password must match.'
            ]);
    }

    /** @test */
    public function change_password_password_must_be_the_same()
    {
        $old_password = 'password';
        $different_password = 'password1';
        $new_password = 'new password';

        $user = User::factory()->create([
            'password' => Hash::make($old_password)
        ]);

        $this->actingAs($user)
            ->patch("/profile/{$user->id}/change-password", [
                'password' => $different_password,
                'new_password' => $new_password,
                'confirm_new_password' => $new_password
            ])
            ->assertSessionHasErrors();
    }
}
