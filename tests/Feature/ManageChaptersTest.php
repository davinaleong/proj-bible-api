<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Log;
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

    /** @test */
    public function user_accessing_showchapter_results_in_redirect()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create();

        $this->actingAs($user)
            ->get(route('chapters.showChapter', ['chapter' => $chapter]))
            ->assertRedirect(route('chapters.show',
                ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]));
    }

    /** @test */
    public function user_can_create_a_chapter()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);
        $chapter = Chapter::factory()->make([
            'book_id' => $book->id
        ]);
        $chapter_id = 1;

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $translation, 'book' => $book]), [
                'number' => $chapter->number,
                'verse_limit' => $chapter->verse_limit
            ])
            ->assertRedirect(route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter_id]))
            ->assertSessionHas('message', 'Chapter created.');

        $this->assertDatabaseHas('chapters', [
            'book_id' => $chapter->book_id,
            'number' => $chapter->number,
            'verse_limit' => $chapter->verse_limit
        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_CHAPTERS,
            'source_id' => 1,
            'message' => "$user->name created chapter $chapter->number for $book->name, $translation->abbr."
        ]);
    }
}
