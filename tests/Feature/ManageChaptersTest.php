<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageChaptersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_endpoints()
    {
        $this->get(route('chapters.showChapter', ['chapter' => 1]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->get(route('chapters.create', ['translation' => $translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('chapters.store', ['translation' => $translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $chapter = Chapter::factory()->create([
            'book_id' => $book->id
        ]);

        $this->get(route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('chapters.edit', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->patch(route('chapters.update', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->delete(route('chapters.destroy', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->actingAs($user)
            ->get(route('chapters.create', ['translation' => $translation, 'book' => $book]))
            ->assertOk();

        $chapter = Chapter::factory()->create([
            'book_id' => $book->id
        ]);

        $this->actingAs($user)
            ->get(route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('chapters.edit', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertOk();
    }
}
