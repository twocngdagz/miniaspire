<?php

namespace Tests\Feature\Api;

use App\Loan;
use App\Repayment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RepaymentCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_the_loan_on_successfully_creating_a_new_repayment()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

        $data = [
            'repayment' => [
                'amount' => 10000,
            ]
        ];

        $response = $this->postJson('/api/loans/'. $loan->id .'/repayments', $data, $this->headers);

        $response->assertStatus(200)
            ->assertJson([
                "repayment" => [
                    "amount" => 10000,
                ]
            ]);
    }

    /** @test */
    public function it_returns_appropriate_field_validation_errors_when_creating_a_new_repayment_with_invalid_inputs()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

        $data = [
            'repayment' => [
                'amount' => '',
            ]
        ];

        $response = $this->postJson('/api/loans/'. $loan->id .'/repayments', $data, $this->headers);

        $response->assertStatus(422)
            ->assertJson([
                'amount' => ['The amount field is required.'],
            ]);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_add_repayment_without_logging_in()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

        $data = [
            'repayment' => [
                'amount' => 10000,
            ]
        ];

        $response = $this->postJson('/api/loans/'. $loan->id .'/repayments', $data, []);

        $response->assertStatus(401);
    }

    /** @test */
    public function it_returns_a_forbidden_error_when_trying_to_create_repayment_to_a_loan_by_others()
    {
        $loan = $this->user->loans()->save(factory(Loan::class)->make());

        $data = [
            'repayment' => [
                'amount' => 10000,
            ]
        ];

        $response = $this->postJson('/api/loans/'. $loan->id .'/repayments', $data, $this->headers);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_should_throw_an_error_when_paying_below_repayment_amount()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make(["amount" => 600, "rate" => 5, "duration" => 12]));

        $data = [
            'repayment' => [
                'amount' => 50,
            ]
        ];

        $response = $this->postJson('/api/loans/'. $loan->id .'/repayments', $data, $this->headers);

        $response->assertStatus(400);
    }

    /** @test */
    public function it_should_throw_an_error_when_paying_on_a_fully_paid_loan()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make(["amount" => 600, "rate" => 5, "duration" => 12]));

        $loan->repayments()->saveMany(factory(Repayment::class)->times(12)->make(["amount" => $loan->repayment()]));

        $data = [
            'repayment' => [
                'amount' => 50,
            ]
        ];

        $response = $this->postJson('/api/loans/'. $loan->id .'/repayments', $data, $this->headers);

        $response->assertStatus(400);
    }
}
