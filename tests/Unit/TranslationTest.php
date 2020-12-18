<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Copyright;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_creator()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $translation->creator);
    }

    /** @test */
    public function has_an_updater()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $translation->updater);
    }

    /** @test */
    public function has_a_copyright()
    {
        $copyright = Copyright::factory()->create();
        $translation = Translation::factory()->create([
            'copyright_id' => $copyright->id
        ]);

        $this->assertInstanceOf(Copyright::class, $translation->copyright);
    }

    /** @test */
    public function has_books()
    {
        $translation = Translation::factory()->create();
        $books = Book::factory()->create([
            'translation_id' => $translation
        ]);

        $this->assertInstanceOf(Book::class, $translation->books[0]);
    }

    /** @test */
    public function get_creator_name()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->name, $translation->getCreatorName());
    }

    /** @test */
    public function get_updater_name()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->name, $translation->getUpdaterName());
    }

    /** @test */
    public function get_creator_name_returns_null_if_no_creator()
    {
        $translation = Translation::factory()->create([
            'created_by' => null
        ]);

        $this->assertEquals('', $translation->getCreatorName());
    }

    /** @test */
    public function get_updater_name_returns_null_if_no_updater()
    {
        $translation = Translation::factory()->create([
            'updated_by' => null
        ]);

        $this->assertEquals('', $translation->getUpdaterName());
    }

    /** @test */
    public function get_copyright_text()
    {
        $copyright = Copyright::factory()->create();
        $translation = Translation::factory()->create([
            'copyright_id' => $copyright->id
        ]);

        $this->assertEquals($copyright->text, $translation->getCopyrightText());
    }

    /** @test */
    public function get_created_at()
    {
        $translation = Translation::factory()->create([
            'created_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $translation->getCreatedAt());
    }

    /** @test */
    public function get_updated_at()
    {
        $translation = Translation::factory()->create([
            'updated_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $translation->getUpdatedAt());
    }
}
