<?php

namespace Tests\Feature;

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
}
