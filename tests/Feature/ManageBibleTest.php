<?php

namespace Tests\Feature;

use App\Models\Copyright;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageBibleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function able_to_get_translations_with_copyright()
    {
        $copyright = Copyright::factory()
            ->create();
        $translations = Translation::factory()
            ->count(2)
            ->create([
                'copyright_id' => $copyright->id
            ]);


        $this->getJson('api/translations')
            ->assertExactJson([
                'translations' => $translations->load('copyright')->jsonSerialize()
            ]);
    }
}
