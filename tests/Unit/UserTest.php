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
        Log::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->assertCount(3, $user->logs);
    }
}
