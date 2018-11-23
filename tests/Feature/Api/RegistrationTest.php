<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_user_with_token_on_valid_registration()
    {
        $data = [
            'user' => [
                'name' => 'test',
                'email' => 'test@test.com',
                'password' => 'secret',
            ]
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'email' => 'test@test.com',
                    'name' => 'test',
                ]
            ]);

        $this->assertArrayHasKey('token', $response->json()['user'], 'Token not found');
    }

    /** @test */
    public function it_returns_field_required_validation_errors_on_invalid_registration()
    {
        $data = [];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]);
    }

    /** @test */
    public function it_returns_field_validation_errors_on_invalid_registration()
    {
        $data = [
            'user' => [
                'name' => 'A very long name that is beyond the max character in the validation',
                'email' => 'invalid email',
                'password' => '1',
            ]
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(422)
            ->assertJson([
                    'name' => ['The name may not be greater than 50 characters.'],
                    'email' => ['The email must be a valid email address.'],
                    'password' => ['The password must be at least 6 characters.'],
            ]);
    }

    /** @test */
    public function it_returns_name_and_email_taken_validation_errors_when_using_duplicate_values_on_registration()
    {
        $data = [
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => 'secret',
            ]
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name has already been taken.'],
                'email' => ['The email has already been taken.'],
            ]);
    }
}
