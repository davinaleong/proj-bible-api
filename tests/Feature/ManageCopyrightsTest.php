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
                'text' => $copyright->text
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('copyright.store'), [
                'name' => $copyright->name
            ])
            ->assertSessionHasErrors([
                'text' => 'The text field is required.'
            ]);
    }
}
