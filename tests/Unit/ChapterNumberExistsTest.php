<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Chapter;
use App\Rules\ChapterNumberExists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterNumberExistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rule_fails_if_value_is_not_an_integer()
    {
        $book = Book::factory()->create();
        $rule = new ChapterNumberExists($book);
        $this->assertFalse($rule->passes('number', 'a'));
    }

    /** @test */
    public function rule_passes_if_chapter_no_doesnt_exist_for_book()
    {
        $book = Book::factory()->create();
        $rule = new ChapterNumberExists($book);
        $this->assertTrue($rule->passes('number', '1'));
    }

    /** @test */
    public function rule_passes_when_chapter_of_different_book_exists()
    {
        $books = Book::factory()->count(2)->create();
        $chapter = Chapter::factory()->create([
            'book_id' => $books[1],
            'number' => 1
        ]);
        $rule = new ChapterNumberExists($books[0]);
        $this->assertTrue($rule->passes('number', $chapter->number));
    }

    /** @test */
    public function rule_fails_when_chapter_of_same_book_exists()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create([
            'book_id' => $book,
            'number' => 1
        ]);
        $rule = new ChapterNumberExists($book);
        $this->assertFalse($rule->passes('number', $chapter->number));
    }

    /** @test */
    public function rule_passes_when_chapter_is_the_same_id()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create([
            'book_id' => $book,
            'number' => 1
        ]);
        $rule = new ChapterNumberExists($book, $chapter);
        $this->assertTrue($rule->passes('number', $chapter->number));
    }
}
