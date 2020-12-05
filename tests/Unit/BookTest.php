<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_creator()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->id, $book->creator->id);
    }

    /** @test */
    public function get_creator_name()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->name, $book->getCreatorName());
    }

    /** @test */
    public function get_creator_name_returns_null_if_no_creator()
    {
        $book = Book::factory()->create([
            'created_by' => null
        ]);

        $this->assertEquals('', $book->getCreatorName());
    }

    /** @test */
    public function has_an_updater()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->id, $book->updater->id);
    }

    /** @test */
    public function get_updater_name()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->name, $book->getUpdaterName());
    }

    /** @test */
    public function get_updater_name_returns_null_if_no_updater()
    {
        $book = Book::factory()->create([
            'updated_by' => null
        ]);

        $this->assertEquals('', $book->getUpdaterName());
    }

    /** @test */
    public function has_a_translation()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->assertEquals($translation->name, $book->translation->name);
    }

    /** @test */
    public function get_translation_name()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->assertEquals($translation->abbr, $book->getTranslationAbbr());
    }

    /** @test */
    public function get_book_returns_book_of_translation_and_number()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation->id
        ]);

        $this->assertEquals($book->id, Book::getBook($translation, $book->number)->id);
    }

    /** @test */
    public function has_chapters()
    {
        $book = Book::factory()->create();
        $chapters = Chapter::factory()->count(2)->create([
            'book_id' => $book->id
        ]);

        $this->assertEquals($chapters->count(), $book->chapters->count());
    }

    /** @test */
    public function get_created_at()
    {
        $book = Book::factory()->create([
            'created_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $book->getCreatedAt());
    }

    /** @test */
    public function get_update_at()
    {
        $book = Book::factory()->create([
            'updated_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $book->getUpdatedAt());
    }
}
