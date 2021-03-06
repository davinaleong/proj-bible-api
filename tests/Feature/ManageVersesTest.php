<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Table;
use App\Models\Translation;
use App\Models\User;
use App\Models\Verse;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageVersesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_access_endpoints()
    {
        $verse = Verse::factory()->create();

        $this->get(route('verses.showVerse', ['verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('verses.create', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->post(route('verses.store', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('verses.show', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->get(route('verses.edit', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->patch(route('verses.update', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->delete(route('verses.destroy', ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();
        $verse = Verse::factory()->create();

        $this->actingAs($user)
            ->get(route('verses.create', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter
            ]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('verses.show', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter,
                'verse' => $verse
            ]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('verses.edit', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter,
                'verse' => $verse
            ]))
            ->assertOk();
    }

    /** @test */
    public function user_accessing_showverse_results_in_redirect()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $verse = Verse::factory()->create();

        $this->actingAs($user)
            ->get(route('verses.showVerse', ['verse' => $verse]))
            ->assertRedirect(route('verses.show',
                ['translation' => $verse->chapter->book->translation, 'book' => $verse->chapter->book, 'chapter' => $verse->chapter, 'verse' => $verse]));
    }

    /** @test */
    public function user_can_create_a_verse()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $verse = Verse::factory()->make();
        $verse_id = 1;

        $this->actingAs($user)
            ->post(route('verses.store', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter
            ]), [
                'number' => $verse->number,
                'passage' => $verse->passage
            ])
            ->assertRedirect(route('verses.show', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter,
                'verse' => $verse_id
            ]))
            ->assertSessionHas('message', 'Verse created.');

        $this->assertDatabaseHas(Table::$TABLE_VERSES, [
            'chapter_id' => $verse->chapter_id,
            'number' => $verse->number,
            'passage' => $verse->passage,
            'created_by' => $user->id,
            'updated_by' => null
        ]);

        $translation = $verse->chapter->book->translation;
        $book = $verse->chapter->book;
        $chapter = $verse->chapter;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_VERSES,
            'source_id' => $verse_id,
            'message' => "$user->name created verse $verse->number for $chapter->number, $book->name, $translation->abbr."
        ]);
    }

    /** @test */
    public function create_verse_returns_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'verse_limit' => 1
        ]);
        $verse = Verse::factory()->make([
            'number' => 1,
            'chapter_id' => $chapter->id
        ]);

        $route = route('verses.store', [
            'translation' => $chapter->book->translation,
            'book' => $chapter->book,
            'chapter' => $chapter
        ]);

        $this->actingAs($user)
            ->post($route, [
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->post($route, [
                'number' => 1
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be a string.'
            ]);

        $this->actingAs($user)
            ->post($route, [
                'number' => '2'
            ])
            ->assertSessionHasErrors([
                'number' => "The number field must be less than or equal to $chapter->verse_limit."
            ]);

        $this->actingAs($user)
            ->post($route, [
                'number' => $verse->number,
                'passage' => ''
            ])
            ->assertSessionHasErrors([
                'passage' => 'The passage field is required.'
            ]);

        Verse::factory()->create([
            'chapter_id' => $verse->chapter_id,
            'number' => $verse->number
        ]);
        $this->actingAs($user)
            ->post($route, [
                'number' => $verse->number
            ])
            ->assertSessionHasErrors([
                'number' => 'The number of the verse exists for the current chapter.'
            ]);
    }

    /** @test */
    public function user_can_update_a_verse()
    {
        $users = User::factory()->count(2)->create();
        $verse = Verse::factory()->create([
            'created_by' => $users[0]->id,
            'updated_by' => null
        ]);
        $updated_verse = Verse::factory()->make();

        $this->actingAs($users[1])
            ->patch(route('verses.update', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter,
                'verse' => $verse
            ]), [
                'number' => $updated_verse->number,
                'passage' => $updated_verse->passage
            ])
            ->assertRedirect(route('verses.show', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter,
                'verse' => $verse
            ]))
            ->assertSessionHas('message', 'Verse updated.');

        $this->assertDatabaseHas(Table::$TABLE_VERSES, [
            'chapter_id' => $verse->chapter_id,
            'number' => $updated_verse->number,
            'passage' => $updated_verse->passage,
            'created_by' => $users[0]->id,
            'updated_by' => $users[1]->id
        ]);

        $user = $users[1];
        $translation = $verse->chapter->book->translation;
        $book = $verse->chapter->book;
        $chapter = $verse->chapter;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_VERSES,
            'source_id' => $verse->id,
            'message' => "$user->name updated verse $verse->number for $chapter->number, $book->name, $translation->abbr."
        ]);
    }

    /** @test */
    public function update_verse_returns_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'verse_limit' => 1
        ]);
        $verse = Verse::factory()->create([
            'chapter_id' => $chapter,
            'number' => '1'
        ]);

        $route = route('verses.update', [
            'translation' => $chapter->book->translation,
            'book' => $chapter->book,
            'chapter' => $chapter,
            'verse' => $verse
        ]);

        $this->actingAs($user)
            ->patch($route, [
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->patch($route, [
                'number' => 1
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be a string.'
            ]);

        $this->actingAs($user)
            ->patch($route, [
                'number' => '2'
            ])
            ->assertSessionHasErrors([
                'number' => "The number field must be less than or equal to $chapter->verse_limit."
            ]);

        $this->actingAs($user)
            ->patch($route, [
                'number' => $verse->number,
                'passage' => ''
            ])
            ->assertSessionHasErrors([
                'passage' => 'The passage field is required.'
            ]);

        $verse2 = Verse::factory([
            'chapter_id' => $verse->chapter,
            'number' => '02'
        ])->create();
        $this->actingAs($user)
            ->patch($route, [
                'number' => $verse2->number,
                'passage' => $verse->passage
            ])
            ->assertSessionHasErrors([
                'number' => 'The number of the verse exists for the current chapter.'
            ]);
    }

    /** @test */
    public function user_can_delete_a_verse()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation
        ]);
        $chapter = Chapter::factory()->create([
            'book_id' => $book
        ]);
        $verse = Verse::factory()->create([
            'chapter_id' => $chapter
        ]);

        $this->actingAs($user)
            ->delete(route('verses.destroy', [
                'translation' => $translation,
                'book' => $book,
                'chapter' => $chapter,
                'verse' => $verse
            ]))
            ->assertRedirect(route('chapters.show', [
                'translation' => $translation,
                'book' => $book,
                'chapter' => $chapter
            ]))
            ->assertSessionHas('message', 'Verse deleted.');

        $this->assertDatabaseMissing(Table::$TABLE_VERSES, $verse->jsonSerialize());

        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_VERSES,
            'source_id' => $verse->id,
            'message' => "$user->name deleted verse $verse->number for $chapter->number, $book->name, $translation->abbr."
        ]);
    }
}
