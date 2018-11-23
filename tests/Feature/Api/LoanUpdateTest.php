<?php

namespace Tests\Feature\Api;

use App\Loan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoanUpdateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_updated_article_on_successfully_updating_the_loan()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

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

        $response = $this->putJson("/api/loans/{$loan->id}", $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                'loan' => [
                    'name' => 'A test loan',
                    'amount' => 10000,
                    'duration' => 6,
                    'fee' => 500,
                    'rate' => 5,
                    'frequency' => 1,
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_updating_the_loan_with_invalid_inputs()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

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

        $response = $this->putJson("/api/loans/{$loan->id}", $data, $this->headers);

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
    public function it_returns_an_unauthorized_error_when_trying_to_update_loan_without_logging_in()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

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

        $response = $this->putJson("/api/loans/{$loan->id}", $data);

        $response->assertStatus(401);
    }

    /** @test */
    public function it_returns_a_forbidden_error_when_trying_to_update_loan_by_others()
    {
        $loan = $this->user->loans()->save(factory(Loan::class)->make());

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

        $response = $this->putJson("/api/loans/{$loan->id}", $data, $this->headers);

        $response->assertStatus(403);
    }
}
