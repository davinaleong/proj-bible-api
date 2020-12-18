<?php

namespace Tests\Unit;

use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_logs()
    {
        $user = User::factory()->create();
        Log::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(Log::class, $user->logs[0]);
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
