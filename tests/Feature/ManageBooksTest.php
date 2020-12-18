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

class ManageBooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_access_endpoints()
    {
        $book = Book::factory()->create();

        $this->get(route('books.showBook', ['book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('books.create', ['translation' => $book->translation]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('books.store', ['translation' => $book->translation]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('books.show', ['translation' => $book->translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->get(route('books.edit', ['translation' => $book->translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->delete(route('books.destroy', ['translation' => $book->translation, 'book' => $book]))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->actingAs($user)
            ->get(route('books.create', ['translation' => $book->translation]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('books.show', ['translation' => $book->translation, 'book' => $book]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('books.edit', ['translation' => $book->translation, 'book' => $book]))
            ->assertOk();
    }

    /** @test */
    public function user_accessing_showbook_results_in_redirect()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->actingAs($user)
            ->get(route('books.showBook', ['book' => $book]))
            ->assertRedirect(route('books.show', ['translation' => $book->translation, 'book' => $book]));
    }

    /** @test */
    public function user_can_create_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->make();

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertRedirect(route('books.show', ['translation' => $book->translation, 'book' => 1]))
            ->assertSessionHas('message', 'Book created.');

        $this->assertDatabaseHas(Table::$TABLE_BOOKS, [
            'translation_id' => $book->translation_id,
            'name' => $book->name,
            'abbr' => $book->abbr,
            'chapter_limit' => $book->chapter_limit,
            'created_by' => $user->id
        ]);

        $abbr = $book->getTranslationAbbr();
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_BOOKS,
            'source_id' => 1,
            'message' => "$user->name created book $book->name for $abbr."
        ]);
    }

    /** @test */
    public function create_book_returns_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $book = Book::factory()->make([
            'number' => 1
        ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => ''
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 'a'
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be an integer.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 0
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be at least 1.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 67
            ])
            ->assertSessionHasErrors([
                'number' => 'The number may not be greater than 66.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => ''
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 'a'
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be an integer.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 0
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be at least 1.'
            ]);

        Book::factory()->create([
            'translation_id' => $book->translation_id,
            'name' => $book->name,
            'abbr' => $book->abbr,
            'number' => $book->number
        ]);
        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertSessionHasErrors([
                'name' => 'The name of the book exists for the current translation.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr of the book exists for the current translation.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $book->translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertSessionHasErrors([
                'number' => 'The number of the book exists for the current translation.'
            ]);
    }

    /** @test */
    public function user_can_update_a_book()
    {
        $users = User::factory()->count(2)->create();
        $book = Book::factory()->create([
            'abbr' => 'Abbr',
            'created_by' => $users[0]->id
        ]);
        $updated_book = Book::factory()->make([
            'translation_id' => $book->translation_id,
            'abbr' => 'Abbr2'
        ]);

        $this->actingAs($users[1])
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $updated_book->name,
                'abbr' => $updated_book->abbr,
                'number' => $updated_book->number,
                'chapter_limit' => $updated_book->chapter_limit
            ])
            ->assertRedirect(route('books.show', ['translation' => $book->translation, 'book' => $book]))
            ->assertSessionHas('message', 'Book updated.');

        $this->assertDatabaseHas(Table::$TABLE_BOOKS, [
            'translation_id' => $book->translation_id,
            'name' => $updated_book->name,
            'abbr' => $updated_book->abbr,
            'chapter_limit' => $updated_book->chapter_limit,
            'created_by' => $users[0]->id,
            'updated_by' => $users[1]->id
        ]);

        $user = $users[1];
        $translation = $book->translation;
        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $users[1]->id,
            'source' => Table::$TABLE_BOOKS,
            'source_id' => 1,
            'message' => "$user->name updated book $updated_book->name for $translation->abbr."
        ]);
    }

    /** @test */
    public function update_book_returns_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => ''
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 'a'
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be an integer.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 0
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be at least 1.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 67
            ])
            ->assertSessionHasErrors([
                'number' => 'The number may not be greater than 66.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => ''
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 'a'
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be an integer.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 0
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be at least 1.'
            ]);

        $book2 = Book::factory()->create([
            'translation_id' => $book->translation_id,
            'name' => 'Book2',
            'abbr' => 'Bk2',
            'number' => 2
        ]);
        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book2->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertSessionHasErrors([
                'name' => 'The name of the book exists for the current translation.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book2->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr of the book exists for the current translation.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $book->translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book2->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertSessionHasErrors([
                'number' => 'The number of the book exists for the current translation.'
            ]);
    }

    /** @test */
    public function user_can_delete_a_book()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation
        ]);
        $chapter = Chapter::factory()->create([
            'book_id' => $book->id
        ]);

        $this->actingAs($user)
            ->delete(route('books.destroy', ['translation' => $translation, 'book' => $book]))
            ->assertRedirect(route('translations.show', ['translation' => $translation]))
            ->assertSessionHas('message', 'Book deleted.');


        $this->assertDatabaseMissing(Table::$TABLE_CHAPTERS, $chapter->jsonSerialize());
        $this->assertDatabaseMissing(Table::$TABLE_BOOKS, $book->jsonSerialize());
        //TODO: Assert deleted verses

        $this->assertDatabaseHas(Table::$TABLE_LOGS, [
            'user_id' => $user->id,
            'source' => Table::$TABLE_BOOKS,
            'source_id' => $book->id,
            'message' => "$user->name deleted book $book->name from $translation->abbr. All book's chapters & verses also deleted."
        ]);
    }
}
