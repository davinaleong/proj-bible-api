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
    public function rule_fails_if_value_is_not_an_integer()
    {
        $translation = Translation::factory()->create();
        $rule = new BookNumberExists($translation);
        $this->assertFalse($rule->passes('number', 'a'));
    }

    /** @test */
    public function rule_passes_when_book_number_of_translation_doesnt_exist()
    {
        $translation = Translation::factory()->create();
        $rule = new BookNumberExists($translation);
        $this->assertTrue($rule->passes('number', '1'));
    }

    /** @test */
    public function rule_passes_when_book_of_different_translation_exists()
    {
        $translations = [
            Translation::factory()->create([
                'abbr' => 'T'
            ]),
            Translation::factory()->create([
                'abbr' => 'T2'
            ])
        ];
        $book = Book::factory()->create([
            'translation_id' => $translations[1]->id,
            'number' => 1
        ]);
        $rule = new BookNumberExists($translations[0]);
        $this->assertTrue($rule->passes('number', $book->number));
    }

    /** @test */
    public function rule_fails_when_book_of_same_translation_exists()
    {
        $book = Book::factory()->create([
            'number' => 1
        ]);
        $rule = new BookNumberExists($book->translation);
        $this->assertFalse($rule->passes('number', $book->number));
    }

    /** @test */
    public function rule_passes_when_book_is_the_same_id()
    {
        $book = Book::factory()->create([
            'number' => 1
        ]);
        $rule = new BookNumberExists($book->translation, $book);
        $this->assertTrue($rule->passes('number', $book->number));
    }
}
