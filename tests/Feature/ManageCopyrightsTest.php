<?php

namespace Tests\Feature;

use App\Models\Copyright;
use App\Models\Log;
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

        $copyright = Copyright::factory()->create([
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
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
            'name' => $copyright->name,
            'text' => $copyright->text,
            'created_by' => $user->id,
            'updated_by' => null
        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_COPYRIGHTS,
            'source_id' => 1,
            'message' => 'Copyright created.'
        ]);
    }

    /** @test */
    public function create_copyright_throws_error_when_data_criteria_not_met()
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

    /** @test */
    public function user_can_edit_a_copyright()
    {
        $users = User::factory()->count(2)->create();
        $copyright = Copyright::factory()->create([
            'created_by' => $users[0]->id
        ]);
        $updated_copyright = Copyright::factory()->make();

        $this->actingAs($users[1])
            ->patch(route('copyright.update', ['copyright' => $copyright]), [
                'name' => $updated_copyright->name,
                'text' => $updated_copyright->text
            ])
            ->assertRedirect(route('copyright.show', ['copyright' => $copyright]))
            ->assertSessionHas('message', 'Copyright updated.');

        $this->assertDatabaseHas('copyrights', [
            'name' => $updated_copyright->name,
            'text' => $updated_copyright->text,
            'created_by' => $users[0]->id,
            'updated_by' => $users[1]->id

        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $users[1]->id,
            'source' => Log::$TABLE_COPYRIGHTS,
            'source_id' => $copyright->id,
            'message' => 'Copyright updated.'
        ]);
    }

    /** @test */
    public function update_copyright_throws_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create();
        $updated_copyright = Copyright::factory()->make();

        $this->actingAs($user)
            ->patch(route('copyright.update', ['copyright' => $copyright]), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('copyright.update', ['copyright' => $copyright]), [
                'name' => $updated_copyright->name,
                'text' => ''
            ])
            ->assertSessionHasErrors([
                'text' => 'The text field is required.'
            ]);
    }
}
