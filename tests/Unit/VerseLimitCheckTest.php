<?php

namespace Tests\Unit;

use App\Models\Chapter;
use App\Rules\VerseLimitCheck;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerseLimitCheckTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rule_passes_if_value_is_a_string()
    {
        $chapter = Chapter::factory()->create();
        $rule = new VerseLimitCheck($chapter);

        $this->assertTrue($rule->passes('number', '1-2'));
    }

    /** @test */
    public function rule_passes_if_value_is_less_or_equal_to_verse_limit()
    {
        $chapter = Chapter::factory()->create([
            'verse_limit' => 1
        ]);
        $rule = new VerseLimitCheck($chapter);

        $this->assertTrue($rule->passes('number', '1'));
    }

    /** @test */
    public function rule_fails_if_value_is_greater_than_verse_limit()
    {
        $chapter = Chapter::factory()->create([
            'verse_limit' => 1
        ]);
        $rule = new VerseLimitCheck($chapter);

        $this->assertFalse($rule->passes('number', '2'));
    }
}
