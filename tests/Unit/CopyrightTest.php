<?php

namespace Tests\Unit;

use App\Models\Copyright;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CopyrightTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_user()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $copyright->user->id);
    }

    /** @test */
    public function get_user_name()
    {
        $user = User::factory()->create();
        $copyright = Copyright::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->name, $copyright->getUserName());
    }
}
