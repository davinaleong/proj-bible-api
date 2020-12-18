<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_creator()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $chapter->creator);
    }

    /** @test */
    public function has_an_updater()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $chapter->updater);
    }

    /** @test */
    public function has_a_book()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create([
            'book_id' => $book->id
        ]);

        $this->assertInstanceOf(Book::class, $chapter->book);
    }

    /** @test */
    public function get_creator_name()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->name, $chapter->getCreatorName());
    }

    /** @test */
    public function get_updater_name()
    {
        $user = User::factory()->create();
        $chapter = Chapter::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->name, $chapter->getUpdaterName());
    }

    /** @test */
    public function get_creator_name_returns_null_if_no_creator()
    {
        $chapter = Chapter::factory()->create([
            'created_by' => null
        ]);

        $this->assertEquals('', $chapter->getCreatorName());
    }

    /** @test */
    public function get_updater_name_returns_null_if_no_updater()
    {
        $chapter = Chapter::factory()->create([
            'updated_by' => null
        ]);

        $this->assertEquals('', $chapter->getUpdaterName());
    }

    /** @test */
    public function get_book_name()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create([
            'book_id' => $book->id
        ]);

        $this->assertEquals($book->name, $chapter->getBookName());
    }

    /** @test */
    public function get_chapter_returns_chapter_of_book_and_number()
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create([
            'book_id' => $book->id,
            'number' => 1
        ]);

        $this->assertEquals($chapter->id, Chapter::getChapter($book, $chapter->number)->id);
    }

    /** @test */
    public function get_created_at()
    {
        $chapter = Chapter::factory()->create([
            'created_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $chapter->getCreatedAt());
    }

    /** @test */
    public function get_updated_at()
    {
        $chapter = Chapter::factory()->create([
            'updated_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $chapter->getUpdatedAt());
    }
}
