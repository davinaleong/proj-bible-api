<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageTranslationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_endpoints()
    {
        $this->get(route('translation.index'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('translation.create'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('translation.store'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('translation.show', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('translation.edit', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch(route('translation.update', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->delete(route('translation.destroy', ['translation' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }
}
