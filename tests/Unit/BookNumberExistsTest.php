<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Translation;
use App\Rules\BookNumberExists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookNumberExistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rule_passes_when_no_book_of_translation_exists()
    {
        $translation = Translation::factory()->create();
        $rule = new BookNumberExists($translation);
        $this->assertTrue($rule->passes('number', 'Book'));
    }

    /** @test */
    public function rule_passes_when_book_of_different_translation_exists()
    {
        $translations = Translation::factory()->count(2)->create();
        $book = Book::factory()->create([
            'translation_id' => $translations[1],
            'number' => 1
        ]);
        $rule = new BookNumberExists($translations[0]);
        $this->assertTrue($rule->passes('number', $book->number));
    }

    /** @test */
    public function rule_fails_when_book_of_same_translation_exists()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation,
            'number' => 1
        ]);
        $rule = new BookNumberExists($translation);
        $this->assertFalse($rule->passes('number', $book->number));
    }

    /** @test */
    public function rule_passes_when_book_is_the_same_id()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation,
            'number' => 1
        ]);
        $rule = new BookNumberExists($translation, $book);
        $this->assertTrue($rule->passes('number', $book->number));
    }
}
