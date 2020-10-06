<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Translation;
use App\Rules\BookNameExists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookNameExistsRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rule_passes_when_no_book_of_translation_exists()
    {
        $translation = Translation::factory()->create();
        $rule = new BookNameExists($translation);
        $this->assertTrue($rule->passes('name', 'Book'));
    }

    /** @test */
    public function rule_passes_when_book_of_different_translation_exists()
    {
        $translations = Translation::factory()->count(2)->create();
        $book = Book::factory()->create([
            'translation_id' => $translations[1],
            'name' => 'Book'
        ]);
        $rule = new BookNameExists($translations[0]);
        $this->assertTrue($rule->passes('name', $book->name));
    }

    /** @test */
    public function rule_fails_when_book_of_same_translation_exists()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation,
            'name' => 'Book'
        ]);
        $rule = new BookNameExists($translation);
        $this->assertFalse($rule->passes('name', $book->name));
    }

    /** @test */
    public function rule_passes_when_book_is_the_same_id()
    {
        $translation = Translation::factory()->create();
        $book = Book::factory()->create([
            'translation_id' => $translation,
            'name' => 'Book'
        ]);
        $rule = new BookNameExists($translation, $book);
        $this->assertTrue($rule->passes('name', $book->name));
    }
}
