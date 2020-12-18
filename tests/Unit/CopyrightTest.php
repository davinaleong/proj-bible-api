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

        $this->assertInstanceOf(User::class, $copyright->creator);
    }

    /** @test */
    public function has_an_updater()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $copyright->updater);
    }

    /** @test */
    public function has_translations()
    {
        $copyright = Copyright::factory()->create();
        Translation::factory()->create([
            'copyright_id' => $copyright->id
        ]);

        $this->assertInstanceOf(Translation::class, $copyright->translations[0]);
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
    public function get_updater_name()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'updated_by' => $user->id
        ]);

        $this->assertEquals($user->name, $copyright->getUpdaterName());
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
    public function get_updater_name_returns_null_if_no_updater()
    {
        $copyright = Copyright::factory()->create([
            'updated_by' => null
        ]);

        $this->assertEquals('', $copyright->getUpdaterName());
    }

    /** @test */
    public function get_created_at()
    {
        $copyright = Copyright::factory()->create([
            'created_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $copyright->getCreatedAt());
    }

    /** @test */
    public function get_updated_at()
    {
        $copyright = Copyright::factory()->create([
            'updated_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $copyright->getUpdatedAt());
    }
}
