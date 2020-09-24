<?php

namespace Tests\Feature;

use App\Models\Copyright;
use App\Models\Log;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageTranslationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_endpoints()
    {
        $this->get(route('translations.index'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('translations.create'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('translations.store'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('translations.show', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('translations.edit', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch(route('translations.update', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->delete(route('translations.destroy', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('translations.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('translations.create'))
            ->assertOk();

        $translation = Translation::factory()->create([
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
        $this->actingAs($user)
            ->get(route('translations.show', ['translation' => $translation]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('translations.edit', ['translation' => $translation]))
            ->assertOk();
    }

    /** @test */
    public function user_can_create_a_translation()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $translation = Translation::factory()->make();

        $this->actingAs($user)
            ->post(route('translations.store'), [
                'name' => $translation->name,
                'abbr' => $translation->abbr,
                'copyright_id' => $translation->copyright_id
            ])
            ->assertRedirect(route('translations.show', ['translation' => 1]))
            ->assertSessionHas('message', 'Translation created.');

        $this->assertDatabaseHas('translations', [
            'name' => $translation->name,
            'abbr' => $translation->abbr,
            'copyright_id' => $translation->copyright_id,
            'created_by' => $user->id,
            'updated_by' => null
        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_TRANSLATIONS,
            'source_id' => 1,
            'message' => 'Translation created.'
        ]);
    }

    /** @test */
    public function create_translation_throws_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->make();

        $this->actingAs($user)
            ->post(route('translations.store'), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('translations.store'), [
                'name' => $translation->name,
                'abbr' => ''
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('translations.store'), [
                'name' => $translation->name,
                'abbr' => $translation->abbr,
                'copyright_id' => ''
            ])
            ->assertSessionHasErrors([
                'copyright_id' => 'The copyright id field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('translations.store'), [
                'name' => $translation->name,
                'abbr' => $translation->abbr,
                'copyright_id' => 2
            ])
            ->assertSessionHasErrors([
                'copyright_id' => "The selected copyright id is invalid."
            ]);
    }
}
