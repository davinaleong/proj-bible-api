<?php

namespace Tests\Feature;

use App\Models\Copyright;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageCopyrightsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_routes()
    {
        $this->get(route('copyright.index'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('copyright.create'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('copyright.store'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('copyright.show', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('copyright.edit', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch(route('copyright.update', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->delete(route('copyright.destroy', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('copyright.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('copyright.create'))
            ->assertOk();

        $copyright = Copyright::factory()->create();
        $this->actingAs($user)
            ->get(route('copyright.show', ['copyright' => $copyright]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('copyright.edit', ['copyright' => $copyright]))
            ->assertOk();
    }

    /** @test */
    public function user_can_create_a_copyright()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->make();

        $this->actingAs($user)
            ->post(route('copyright.store'), [
                'name' => $copyright->name,
                'text' => $copyright->text
            ])
            ->assertRedirect(route('copyright.show', ['copyright' => 1]))
            ->assertSessionHas('message', 'Copyright created.');

        $this->assertDatabaseHas('copyrights', [
            'user_id' => $user->id,
            'name' => $copyright->name,
            'text' => $copyright->text
        ]);
    }

    /** @test */
    public function create_user_throws_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->make();

        $this->actingAs($user)
            ->post(route('copyright.store'), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('copyright.store'), [
                'name' => $copyright->name,
                'text' => ''
            ])
            ->assertSessionHasErrors([
                'text' => 'The text field is required.'
            ]);
    }
}
