<?php

namespace Tests\Unit;

use App\Models\Book;
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
}
