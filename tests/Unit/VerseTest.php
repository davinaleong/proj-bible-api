<?php

namespace Tests\Unit;

use App\Models\Chapter;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class VerseTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function truncate_passage()
    {
        $verse = Verse::factory()->create([
            'passage' => $this->faker->words(51, true)
        ]);
        $this->assertEquals(Str::words($verse->passage, 50), $verse->truncatePassage());
    }

    /** @test */
    public function has_a_creator()
    {
        $verse = Verse::factory()->create();

        $this->assertInstanceOf(User::class, $verse->creator);
    }

    /** @test */
    public function has_an_updater()
    {
        $verse = Verse::factory()->create();

        $this->assertInstanceOf(User::class, $verse->updater);
    }

    /** @test */
    public function has_a_chapter()
    {
        $verse = Verse::factory()->create();

        $this->assertInstanceOf(Chapter::class, $verse->chapter);
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
    public function get_chapter_number()
    {
        $chapter = Chapter::factory()->create();
        $verse = Verse::factory()->create([
            'chapter_id' => $chapter->id
        ]);

        $this->assertEquals($chapter->number, $verse->getChapterNumber());
    }

    /** @test */
    public function get_verse_returns_verse_of_chapter_and_number()
    {
        $chapter = Chapter::factory()->create();
        $verse = Verse::factory()->create([
            'chapter_id' => $chapter->id,
            'number' => 1
        ]);

        $this->assertEquals($verse->id, Verse::getVerse($chapter, $verse->number)->id);
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
