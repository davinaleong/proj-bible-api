<?php

namespace Tests\Unit;

use App\Models\Copyright;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CopyrightTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_creator()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->id, $copyright->creator->id);
    }

    /** @test */
    public function get_creator_name()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'created_by' => $user->id
        ]);

        $this->assertEquals($user->name, $copyright->getCreatorName());
    }

    /** @test */
    public function get_creator_name_returns_null_if_no_creator()
    {
        $copyright = Copyright::factory()->create([
            'created_by' => null
        ]);

        $this->assertEquals('', $copyright->getCreatorName());
    }

    /** @test */
    public function has_an_updater()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->id, $copyright->updater->id);
    }

    /** @test */
    public function get_updater_name()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->name, $copyright->getUpdaterName());
    }

    /** @test */
    public function get_updater_name_returns_null_if_no_updater()
    {
        $copyright = Copyright::factory()->create([
            'updated_by' => null
        ]);

        $this->assertEquals('', $copyright->getUpdaterName());
    }

    /** @test */
    public function has_translations()
    {
        $copyright = Copyright::factory()->create();
        Translation::factory()->count(2)->create([
            'copyright_id' => $copyright->id
        ]);

        $this->assertCount(2, $copyright->translations);
    }
}
