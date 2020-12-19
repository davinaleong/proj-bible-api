<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Log;
use App\Models\Table;
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
        $chapter = Chapter::factory()->create();

        $this->get(route('chapters.showChapter', ['chapter' => $chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

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

        $this->assertDatabaseHas(Table::$TABLE_CHAPTERS, [
            'book_id' => $chapter->book_id,
            'number' => $chapter->number,
            'verse_limit' => $chapter->verse_limit,
            'created_by' => $user->id,
            'updated_by' => null
        ]);

        $translation = $chapter->book->translation;
        $book = $chapter->book;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_CHAPTERS,
            'source_id' => 1,
            'message' => "$user->name created chapter $chapter->number for $book->name, $translation->abbr."
        ]);
    }

    /** @test */
    public function create_chapter_returns_error_when_data_critera_not_met()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->make([
            'number' => 1
        ]);

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

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => $chapter->number,
                'verse_limit' => ''
            ])
            ->assertSessionHasErrors([
                'verse_limit' => 'The verse limit field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => $chapter->number,
                'verse_limit' => 'a'
            ])
            ->assertSessionHasErrors([
                'verse_limit' => 'The verse limit must be an integer.'
            ]);

        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => $chapter->number,
                'verse_limit' => 0
            ])
            ->assertSessionHasErrors([
                'verse_limit' => 'The verse limit must be at least 1.'
            ]);

        Chapter::factory()->create([
            'book_id' => $chapter->book_id,
            'number' => $chapter->number
        ]);
        $this->actingAs($user)
            ->post(route('chapters.store', ['translation' => $chapter->book->translation, 'book' => $chapter->book]), [
                'number' => $chapter->number,
                'verse_limit' => 0
            ])
            ->assertSessionHasErrors([
                'number' => 'The number of the chapter exists for the current book.'
            ]);
    }

    /** @test */
    public function user_can_update_a_chapter()
    {
        $users = User::factory()->count(2)->create();
        $chapter = Chapter::factory()->create([
            'number' => 1,
            'created_by' => $users[0]->id
        ]);
        $updated_chapter = Chapter::factory()->make([
            'book_id' => $chapter->book_id,
            'number' => 2
        ]);

        $this->actingAs($users[1])
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => $updated_chapter->number,
                'verse_limit' => $updated_chapter->verse_limit
            ])
            ->assertSessionHas([
                'message' => 'Chapter updated.'
            ]);

        $this->assertDatabaseHas(Table::$TABLE_CHAPTERS, [
            'book_id' => $chapter->book_id,
            'number' => $updated_chapter->number,
            'verse_limit' => $updated_chapter->verse_limit,
            'created_by' => $users[0]->id,
            'updated_by' => $users[1]->id
        ]);

        $user = $users[1];
        $translation = $chapter->book->translation;
        $book = $chapter->book;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_CHAPTERS,
            'source_id' => 1,
            'message' => "$user->name updated chapter $updated_chapter->number for $book->name, $translation->abbr."
        ]);
    }

    /** @test */
    public function update_chapter_returns_error_if_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'number' => 1
        ]);

        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => '',
                'verse_limit' => $chapter->verse_limit
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => 'a',
                'verse_limit' => $chapter->verse_limit
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be an integer.'
            ]);

        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => 0,
                'verse_limit' => $chapter->verse_limit
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be at least 1.'
            ]);

        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => $chapter->number,
                'verse_limit' => ''
            ])
            ->assertSessionHasErrors([
                'verse_limit' => 'The verse limit field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => $chapter->number,
                'verse_limit' => 'a'
            ])
            ->assertSessionHasErrors([
                'verse_limit' => 'The verse limit must be an integer.'
            ]);

        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => $chapter->number,
                'verse_limit' => 0
            ])
            ->assertSessionHasErrors([
                'verse_limit' => 'The verse limit must be at least 1.'
            ]);

        $chapter2 = Chapter::factory()->create([
            'book_id' => $chapter->book_id,
            'number' => 2
        ]);
        $this->actingAs($user)
            ->patch(route('chapters.update', ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]), [
                'number' => $chapter2->number,
                'verse_limit' => $chapter->verse_limit
            ])
            ->assertSessionHasErrors([
                'number' => 'The number of the chapter exists for the current book.'
            ]);
    }

    /** @test */
    public function user_can_delete_a_chapter()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);
        $chapter = Chapter::factory()->create([
            'book_id' => $book
        ]);

        $this->actingAs($user)
            ->delete(route('chapters.destroy', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter]))
            ->assertSessionHas([
                'message' => 'Chapter deleted.'
            ])
            ->assertRedirect(route('books.show', ['translation' => $translation, 'book' => $book]));

        $this->assertDatabaseMissing(Table::$TABLE_CHAPTERS, $chapter->jsonSerialize());
        //TODO: Assert deleted verses

        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_CHAPTERS,
            'source_id' => 1,
            'message' => "$user->name deleted chapter $chapter->number for $book->name, $translation->abbr."
        ]);

        //TODO: Assert deleted verse log
    }
}
