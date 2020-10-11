<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Translation;
use App\Rules\BookAbbrExists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookAbbrExistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rule_passes_when_no_book_of_translation_exists()
    {
        $translation = Translation::factory()->create();
        $rule = new BookAbbrExists($translation);
        $this->assertTrue($rule->passes('name', 'Book'));
    }

    /** @test */
    public function rule_passes_when_book_of_different_translation_exists()
    {
        $translations = Translation::factory()->count(2)->create();
        $book = Book::factory()->create([
            'translation_id' => $translations[1],
            'abbr' => 'Bk'
        ]);
        $rule = new BookAbbrExists($translations[0]);
        $this->assertTrue($rule->passes('name', $book->abbr));
    }

    /** @test */
    public function rule_fails_when_book_of_same_translation_exists()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation,
            'name' => 'Bk'
        ]);
        $rule = new BookAbbrExists($translation);
        $this->assertFalse($rule->passes('name', $book->abbr));
    }

    /** @test */
    public function rule_passes_when_book_is_the_same_id()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation,
            'abbr' => 'Bk'
        ]);
        $rule = new BookAbbrExists($translation, $book);
        $this->assertTrue($rule->passes('name', $book->abbr));
    }
}