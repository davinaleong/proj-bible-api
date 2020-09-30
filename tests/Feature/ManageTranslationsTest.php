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

        $different_translation = Translation::factory()->create();
        $this->actingAs($user)
            ->post(route('translations.store'), [
                'name' => $translation->name,
                'abbr' => $different_translation->abbr
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr has already been taken.'
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
                'copyright_id' => 3
            ])
            ->assertSessionHasErrors([
                'copyright_id' => "The selected copyright id is invalid."
            ]);
    }

    /** @test */
    public function user_can_edit_a_translation()
    {
        $users = User::factory()->count(2)->create();
        $translation = Translation::factory()->create([
            'created_by' => $users[0]->id
        ]);
        $updated_translation = translation::factory()->make();

        $this->actingAs($users[1])
            ->patch(route('translations.update', ['translation' => $translation]), [
                'name' => $updated_translation->name,
                'abbr' => $updated_translation->abbr,
                'copyright_id' => $translation->copyright_id
            ])
            ->assertRedirect(route('translations.show', ['translation' => $translation]))
            ->assertSessionHas('message', 'Translation updated.');

        $this->assertDatabaseHas('translations', [
            'name' => $updated_translation->name,
            'abbr' => $updated_translation->abbr,
            'copyright_id' => $translation->copyright_id,
            'created_by' => $users[0]->id,
            'updated_by' => $users[1]->id
        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $users[1]->id,
            'source' => Log::$TABLE_TRANSLATIONS,
            'source_id' => $translation->id,
            'message' => 'Translation updated.'
        ]);
    }

    /** @test */
    public function update_translation_throws_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();

        $this->actingAs($user)
            ->patch(route('translations.update', ['translation' => $translation]), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('translations.update', ['translation' => $translation]), [
                'name' => $translation->name,
                'abbr' => ''
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr field is required.'
            ]);

        $different_translation = Translation::factory()->create();
        $this->actingAs($user)
            ->patch(route('translations.update', ['translation' => $translation]), [
                'name' => $translation->name,
                'abbr' => $different_translation->abbr
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr has already been taken.'
            ]);

        $this->actingAs($user)
            ->patch(route('translations.update', ['translation' => $translation]), [
                'name' => $translation->name,
                'abbr' => $translation->abbr,
                'copyright_id' => ''
            ])
            ->assertSessionHasErrors([
                'copyright_id' => 'The copyright id field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('translations.update', ['translation' => $translation]), [
                'name' => $translation->name,
                'abbr' => $translation->abbr,
                'copyright_id' => 3
            ])
            ->assertSessionHasErrors([
                'copyright_id' => "The selected copyright id is invalid."
            ]);
    }

    /** @test */
    public function user_can_delete_a_translation()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();

        $this->actingAs($user)
            ->delete(route('translations.destroy', ['translation' => $translation]))
            ->assertRedirect(route('translations.index'))
            ->assertSessionHas('message', 'Translation deleted.');

        $this->assertDatabaseMissing('translations', $translation->jsonSerialize());

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_TRANSLATIONS,
            'source_id' => $translation->id,
            'message' => 'Translation deleted.'
        ]);
    }
}