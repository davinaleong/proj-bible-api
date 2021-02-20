<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Copyright;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageBibleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_translations_with_copyright()
    {
        $copyright = Copyright::factory()
            ->create();
        $translations = Translation::factory()
            ->count(2)
            ->create([
                'copyright_id' => $copyright->id
            ]);


        $this->getJson("api/translations")
            ->assertExactJson([
                'translations' => $translations->load('copyright')->jsonSerialize()
            ]);
    }

    /** @test */
    public function can_get_one_translation_with_copyright()
    {
        $copyright = Copyright::factory()
            ->create();
        $translation = Translation::factory()
            ->create([
                'copyright_id' => $copyright->id
            ]);


        $this->getJson("api/translations/$translation->abbr")
            ->assertExactJson([
                'translation' => $translation->load('copyright')->jsonSerialize()
            ]);
    }

    /** @test */
    public function can_get_all_books_of_a_translation()
    {
        $translation = Translation::factory()
            ->create();
        $books = [
            Book::factory()
                ->create([
                    'number' => 2,
                    'translation_id' => $translation->id
                ]),
            Book::factory()
                ->create([
                    'number' => 1,
                    'translation_id' => $translation->id
                ])
        ];

        $this->getJson("api/translations/{$translation->abbr}/books")
            ->assertExactJson([
                'translation' => $translation->load('copyright')->jsonSerialize(),
                'books' => [
                    $books[1]->jsonSerialize(),
                    $books[0]->jsonSerialize()
                ]
            ]);
    }

    /** @test */
    public function can_get_a_book_from_a_translation()
    {
        $translation = Translation::factory()
            ->create();
        $book = Book::factory()
            ->create([
                'translation_id' => $translation->id
            ]);

        $this->getJson("api/translations/{$translation->abbr}/books/{$book->name}")
            ->assertExactJson([
                'translation' => $translation->load('copyright')->jsonSerialize(),
                'book' => $book->jsonSerialize()
            ]);
    }

    /** @test */
    public function get_all_chapters_of_a_book()
    {
        $translation = Translation::factory()
            ->create();
        $book = Book::factory()
            ->create([
                'translation_id' => $translation->id
            ]);
        $chapters = [
            Chapter::factory()
                ->create([
                    'book_id' => $book->id,
                    'number' => 2
                ]),
            Chapter::factory()
                ->create([
                    'book_id' => $book->id,
                    'number' => 1
                ])
        ];

        $this->getJson("api/translations/$translation->abbr/books/$book->name/chapters")
            ->assertExactJson([
                'translation' => $translation->load('copyright')->jsonSerialize(),
                'book' => $book->jsonSerialize(),
                'chapters' => [
                    $chapters[1]->jsonSerialize(),
                    $chapters[0]->jsonSerialize()
                ]
            ]);
    }
}
