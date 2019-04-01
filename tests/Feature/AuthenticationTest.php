<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    /**
     * A basic login test.
     *
     * @return void
     */
    public function testCanLogin()
    {
        $user = User::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/auth/login', ['email' => $user->email, 'password' => 'secret']);
        $response->assertStatus(200)->assertJson(['token_type' => 'bearer']);
    }

    public function testCanRegisterUser(){
        $user = \factory(\App\User::class)->make(['password' => 'secret']);
        
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/auth/register', [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'surname' => $user->surname,
            'password' => $user->password
        ]);
        $response->assertStatus(200);
        User::where('email', $user->email)->delete();
        // $user->delete();
    }
}
