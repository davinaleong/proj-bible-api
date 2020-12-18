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

        $this->assertInstanceOf(User::class, $log->user);
    }

    /** @test */
    public function get_created_at()
    {
        $log = Log::factory()->create([
            'created_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $log->getCreatedAt());
    }

    /** @test */
    public function get_updated_at()
    {
        $log = Log::factory()->create([
            'updated_at' => '2020-09-10 12:00:00'
        ]);

        $this->assertEquals('12:00:00 10-09-2020', $log->getUpdatedAt());
    }
}
