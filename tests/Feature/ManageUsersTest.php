<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ManageUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_access_endpoints()
    {
        $this->get('/users')
            ->assertStatus(302);
    }

    /** @test */
    public function user_can_access_endpoints()
    {
        $this->withoutExceptionHandling();
        $role = Role::factory()->create();
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);

        $this->actingAs($user)
            ->get('/users')
            ->assertOk();
    }
}
