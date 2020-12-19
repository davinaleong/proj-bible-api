<?php

namespace Tests\Unit;

use App\Models\Chapter;
use App\Models\Verse;
use App\Rules\VerseNumberExists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerseNumberExistsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rule_passes_when_verse_number_of_chapter_doesnt_exist()
    {
        $chapter = Chapter::factory()->create();
        $rule = new VerseNumberExists($chapter);
        $this->assertTrue($rule->passes('number', '01'));
    }

    /** @test */
    public function rule_passes_when_verse_of_a_different_chapter_exists()
    {
        $chapters = Chapter::factory()->count(2)->create();
        $verse = Verse::factory()->create([
            'chapter_id' => $chapters[1],
            'number' => '01'
        ]);
        $rule = new VerseNumberExists($chapters[0]);
        $this->assertTrue($rule->passes('number', $verse->number));
    }

    /** @test */
    public function rule_fails_when_verse_of_the_same_chapter_exists()
    {
        $chapter = Chapter::factory()->create();
        $verse = Verse::factory()->create([
            'chapter_id' => $chapter,
            'number' => '01'
        ]);
        $rule = new VerseNumberExists($chapter);
        $this->assertFalse($rule->passes('number', $verse->number));
    }

    /** @test */
    public function rule_passes_when_verse_is_the_same_id()
    {
        $chapter = Chapter::factory()->create();
        $verse = Verse::factory()->create([
            'chapter_id' => $chapter,
            'number' => '01'
        ]);
        $rule = new VerseNumberExists($chapter, $verse);
        $this->assertTrue($rule->passes('number', $verse->number));
    }
}
