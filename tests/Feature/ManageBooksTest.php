<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Log;
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
        $this->get(route('books.showBook', ['book' => 1]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $translation = Translation::factory()->create();

        $this->get(route('books.create', ['translation' => $translation]))
            ->assertStatus(302)
            ->assertRedirect('login');

        $this->post(route('books.store', ['translation' => $translation]))
            ->assertStatus(302)
            ->assertRedirect('login');

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
        $translation = Translation::factory()->create();
        $book = Book::factory()->make([
            'translation_id' => $translation->id
        ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertRedirect(route('books.show', ['translation' => $translation, 'book' => 1]))
            ->assertSessionHas('message', 'Book created.');

        $this->assertDatabaseHas('books', [
            'translation_id' => $book->translation_id,
            'name' => $book->name,
            'abbr' => $book->abbr,
            'chapter_limit' => $book->chapter_limit
        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_BOOKS,
            'source_id' => 1,
            'message' => "$user->name created $book->name for $translation->abbr."
        ]);
    }

    /** @test */
    public function create_book_returns_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->make([
            'translation_id' => $translation->id
        ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => ''
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 'a'
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be an integer.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 0
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be at least 1.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 67
            ])
            ->assertSessionHasErrors([
                'number' => 'The number may not be greater than 66.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => ''
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit field is required.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 'a'
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be an integer.'
            ]);

        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 0
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be at least 1.'
            ]);

        $book2 = Book::factory()->create([
            'translation_id' => $translation->id,
            'name' => 'Book2',
            'abbr' => 'Bk2',
            'number' => 2
        ]);
        $this->actingAs($user)
            ->post(route('books.store', ['translation' => $translation]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book2->number,
                'chapter_limit' => $book->chapter_limit
            ]);
        $errors = session('errors');
        $messages = $errors->getBag('default')->getMessages();
        $this->assertEquals('Number exists for current translation.', $messages[0][0]);
    }

    /** @test */
    public function user_can_update_a_book()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => $book->chapter_limit
            ])
            ->assertRedirect(route('books.show', ['translation' => $translation, 'book' => $book]))
            ->assertSessionHas('message', 'Book updated.');

        $this->assertDatabaseHas('books', [
            'translation_id' => $book->translation_id,
            'name' => $book->name,
            'abbr' => $book->abbr,
            'chapter_limit' => $book->chapter_limit
        ]);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'source' => Log::$TABLE_BOOKS,
            'source_id' => 1,
            'message' => "$user->name updated $book->name for $translation->abbr."
        ]);
    }

    /** @test */
    public function update_book_returns_error_when_data_criteria_not_met()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => ''
            ])
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => ''
            ])
            ->assertSessionHasErrors([
                'abbr' => 'The abbr field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => ''
            ])
            ->assertSessionHasErrors([
                'number' => 'The number field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 'a'
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be an integer.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 0
            ])
            ->assertSessionHasErrors([
                'number' => 'The number must be at least 1.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => 67
            ])
            ->assertSessionHasErrors([
                'number' => 'The number may not be greater than 66.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => ''
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit field is required.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 'a'
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be an integer.'
            ]);

        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book->number,
                'chapter_limit' => 0
            ])
            ->assertSessionHasErrors([
                'chapter_limit' => 'The chapter limit must be at least 1.'
            ]);

        $book2 = Book::factory()->create([
            'translation_id' => $translation->id,
            'name' => 'Book2',
            'abbr' => 'Bk2',
            'number' => 2
        ]);
        $this->actingAs($user)
            ->patch(route('books.update', ['translation' => $translation, 'book' => $book]), [
                'name' => $book->name,
                'abbr' => $book->abbr,
                'number' => $book2->number,
                'chapter_limit' => $book->chapter_limit
            ]);
        $errors = session('errors');
        $messages = $errors->getBag('default')->getMessages();
        $this->assertEquals('Number exists for current translation.', $messages[0][0]);
    }
    // TODO: Test delete book
}
