<?php

namespace Tests\Unit;

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

        $this->assertEquals($user->id, $translation->creator->id);
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
    public function get_creator_name_returns_null_if_no_creator()
    {
        $translation = Translation::factory()->create([
            'created_by' => null
        ]);

        $this->assertEquals('', $translation->getCreatorName());
    }

    /** @test */
    public function has_a_updater()
    {
        $user = User::factory()->create();
        $translation = Translation::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->id, $translation->updater->id);
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
    public function get_updater_name_returns_null_if_no_updater()
    {
        $translation = Translation::factory()->create([
            'updated_by' => null
        ]);

        $this->assertEquals('', $translation->getUpdaterName());
    }

    /** @test */
    public function has_a_copyright()
    {
        $copyright = Copyright::factory()->create();
        $translation = Translation::factory()->create([
            'copyright_id' => $copyright->id
        ]);

        $this->assertEquals($copyright->name, $translation->copyright->name);
    }

    /** @test */
    public function get_copyright_name()
    {
        $copyright = Copyright::factory()->create();
        $translation = Translation::factory()->create([
            'copyright_id' => $copyright->id
        ]);

        $this->assertEquals($copyright->name, $translation->getCopyrightName());
    }
}
