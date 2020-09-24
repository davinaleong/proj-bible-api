<?php

namespace Tests\Unit;

use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_user()
    {
        $user = User::factory()->create();
        $log = Log::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($log->user_id, $log->user->id);
    }
}
