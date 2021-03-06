<?php

namespace Tests\Feature;

use App\Models\Copyright;
use App\Models\Log;
use App\Models\Table;
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
        $this->get(route('copyrights.index'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('copyrights.create'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('copyrights.store'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('copyrights.show', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('copyrights.edit', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch(route('copyrights.update', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->delete(route('copyrights.destroy', ['copyright' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('copyrights.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('copyrights.create'))
            ->assertOk();

        $copyright = Copyright::factory()->create([
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
        $this->actingAs($user)
            ->get(route('copyrights.show', ['copyright' => $copyright]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('copyrights.edit', ['copyright' => $copyright]))
            ->assertOk();
    }

    /** @test */
    public function user_can_create_a_copyright()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->make();

        $this->actingAs($user)
            ->post(route('copyrights.store'), [
                'name' => $copyright->name,
                'text' => $copyright->text
            ])
            ->assertRedirect(route('copyrights.show', ['copyright' => 1]))
            ->assertSessionHas('message', 'Copyright created.');

        $this->assertDatabaseHas(Table::$TABLE_COPYRIGHTS, [
            'name' => $copyright->name,
            'text' => $copyright->text,
            'created_by' => $user->id,
            'updated_by' => null
        ]);

        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_COPYRIGHTS,
            'source_id' => 1,
            'message' => "$user->name created copyright $copyright->name."
        ]);
    }

    /** @test */
    public function create_copyright_throws_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->make();

        $route = route('copyrights.store');
        $this->actingAs($user)
            ->post($route, [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->post($route, [
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
            ->patch(route('copyrights.update', ['copyright' => $copyright]), [
                'name' => $updated_copyright->name,
                'text' => $updated_copyright->text
            ])
            ->assertRedirect(route('copyrights.show', ['copyright' => $copyright]))
            ->assertSessionHas('message', 'Copyright updated.');

        $this->assertDatabaseHas(Table::$TABLE_COPYRIGHTS, [
            'name' => $updated_copyright->name,
            'text' => $updated_copyright->text,
            'created_by' => $users[0]->id,
            'updated_by' => $users[1]->id
        ]);

        $name = $users[1]->name;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $users[1]->id,
            'source' => Table::$TABLE_COPYRIGHTS,
            'source_id' => $copyright->id,
            'message' => "$name updated copyright $updated_copyright->name."
        ]);
    }

    /** @test */
    public function update_copyright_throws_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create();
        $updated_copyright = Copyright::factory()->make();

        $route = route('copyrights.update', ['copyright' => $copyright]);
        $this->actingAs($user)
            ->patch($route, [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->patch($route, [
                'name' => $updated_copyright->name,
                'text' => ''
            ])
            ->assertSessionHasErrors([
                'text' => 'The text field is required.'
            ]);
    }

    /** @test */
    public function user_can_delete_a_copyright()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create();

        $this->actingAs($user)
            ->delete(route('copyrights.destroy', ['copyright' => $copyright]))
            ->assertRedirect(route('copyrights.index'))
            ->assertSessionHas('message', 'Copyright deleted.');

        $this->assertDatabaseMissing(Table::$TABLE_COPYRIGHTS, $copyright->jsonSerialize());

        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_COPYRIGHTS,
            'source_id' => $copyright->id,
            'message' => "$user->name deleted copyright $copyright->name."
        ]);
    }
}
