<?php

namespace Tests\Unit;

use App\Rules\PasswordHash;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordHashTest extends TestCase
{
    /** @test */
    public function rule_passes_if_password_is_correct()
    {
        $password = 'password';
        $hash = Hash::make($password);
        $rule = new PasswordHash($hash);

        $this->assertTrue($rule->passes('password', $password));
    }

    /** @test */
    public function rule_fails_if_password_is_incorrect()
    {
        $password = 'password';
        $hash = Hash::make($password);
        $rule = new PasswordHash($hash);

        $this->assertFalse($rule->passes('password', $password . '1'));
    }
}
