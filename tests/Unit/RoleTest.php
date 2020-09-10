<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_have_a_user()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        $this->assertEquals($user->id, $role->user->id);
    }
}
