<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_a_role()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        $this->assertEquals($user->role_id, $user->role->id);
    }
}
