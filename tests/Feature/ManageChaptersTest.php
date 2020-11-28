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

        $chapter = Chapter::factory()->create();

        $this->get(route('chapters.create', ['translation' => $chapter->book->translation, 'book' => $chapter->book]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('chapters.show', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('chapters.edit', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->delete(route('chapters.destroy', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();

        $chapter = Chapter::factory()->create();

        $this->actingAs($user)
            ->get(route('chapters.create', ['translation' => $chapter->book->translation, 'book' => $chapter->book]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('chapters.show', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('chapters.edit', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]))
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
        $chapter = Chapter::factory()->make();
        $chapter_id = 1;

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => $chapter->number,
                'verse_limit' => $chapter->verse_limit
            ])
            ->assertRedirect(route('chapters.show', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter_id]))
            ->assertSessionHas('message', 'Chapter created.');

        $this->assertDatabaseHas('chapters', [
            'book_id' => $chapter->book_id,
            'number' => $chapter->number,
            'verse_limit' => $chapter->verse_limit
        ]);

        $translation = $chapter->book->translation;
        $book = $chapter->book;
        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_CHAPTERS,
            'source_id' => 1,
            'message' => "$user->name created chapter $chapter->number for $book->name, $translation->abbr."
        ]);
    }

    /** @test */
    public function create_chapter_returns_error_when_data_critera_not_met()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->make();

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => 'a'
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be an integer.'
            ]);

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => '0'
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be at least 1.'
            ]);

        $book = $chapter->book;
        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => $book->chapter_limit + 1
            ])
            ->assertSessionHasErrors([
                'number' => "The number may not be greater than $book->chapter_limit."
            ]);
    }
}
