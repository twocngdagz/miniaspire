<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoanCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_loan_on_successfully_creating_a_new_loan()
    {
        $data = [
            'loan' => [
                'name' => 'A test loan',
                'amount' => 10000,
                'duration' => 6,
                'fee' => 500,
                'rate' => 5,
                'frequency' => 1,
            ]
        ];

        $response = $this->postJson('/api/loans', $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                "loan" => [
                    "name" => "A test loan",
                    "amount" => 10000,
                    "duration" => 6,
                    "fee" => 500,
                    "rate" => 5,
                    "frequency" => 1
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_creating_a_new_article_with_invalid_inputs()
    {
        $data = [
            'loan' => [
                'name' => '',
                'amount' => '',
                'duration' => '',
                'fee' => '',
                'rate' => '',
                'frequency' => '',
            ]
        ];

        $response = $this->postJson('/api/loans', $data, $this->headers);

        $response->assertStatus(422)
            ->assertJson([
                'name' => ['The name field is required.'],
                'amount' => ['The amount field is required.'],
                'duration' => ['The duration field is required.'],
                'fee' => ['The fee field is required.'],
                'rate' => ['The rate field is required.'],
                'frequency' => ['The frequency field is required.'],
            ]);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_add_article_without_logging_in()
    {
        $response = $this->postJson('/api/loans', []);

        $response->assertStatus(401);
    }
}
