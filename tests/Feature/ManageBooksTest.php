<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageBooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_endpoints()
    {
        $this->get(route('books.index'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('books.create'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('books.store'))
            ->assertStatus(302)
            ->assertRedirect('login');

        $translation = Translation::factory()->create();
        $this->get(route('books.show', ['translation' => $translation, 'book' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('books.edit', ['translation' => $translation, 'book' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch(route('books.update', ['translation' => $translation, 'book' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->delete(route('books.destroy', ['translation' => $translation, 'book' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();

        $this->actingAs($user)
            ->get(route('books.index'), ['translation' => $translation])
            ->assertOk();

        $this->actingAs($user)
            ->get(route('books.create', ['translation' => $translation]))
            ->assertOk();

        $book = Book::factory()->create([
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
        $this->actingAs($user)
            ->get(route('books.show', ['translation' => $translation, 'book' => $book]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('books.edit', ['translation' => $translation, 'book' => $book]))
            ->assertOk();
    }
}
