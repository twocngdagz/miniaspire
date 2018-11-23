<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_current_user_when_logged_in()
    {
        $response = $this->getJson('/api/user', $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'email' => $this->loggedInUser->email,
                    'name' => $this->loggedInUser->name
                ]
            ]);
    }

    /** @test */
    public function it_returns_unauthenticated_error_when_using_a_wrong_token()
    {
        $response = $this->getJson('/api/user', [
            'Authorization' => 'wrongtoken'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_not_logged_in()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_returns_the_updated_user_on_updating()
    {
        $data = [
            'user' => [
                'name' => 'test12345',
                'email' => 'test12345@test.com',
                'password' => 'test12345',
            ]
        ];

        $response = $this->putJson('/api/user', $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'name' => 'test12345',
                    'email' => 'test12345@test.com',
                ]
            ]);
    }

    /** @test */
    public function it_returns_field_validation_errors_on_updating()
    {
        $data = [
            'user' => [
                'name' => 'A very long name that is beyond the max character in the validation',
                'email' => 'invalid email passing by',
                'password' => '',
            ]
        ];

        $response = $this->putJson('/api/user', $data, $this->headers);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name may not be greater than 50 characters.'],
                'email' => ['The email must be a valid email address.'],
                'password' => ['The password must be at least 6 characters.'],
            ]);
    }

    /** @test */
    public function it_returns_name_and_email_taken_validation_errors_when_using_duplicate_values_on_updating()
    {
        $data = [
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => 'secret',
            ]
        ];

        $response = $this->putJson('/api/user', $data, $this->headers);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name has already been taken.'],
                'email' => ['The email has already been taken.'],
            ]);
    }
}
