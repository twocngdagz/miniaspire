<?php

namespace Tests\Feature\Api;

use App\Loan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoanDeleteTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_a_200_success_response_on_successfully_removing_the_loan()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

        $response = $this->deleteJson("/api/loans/{$loan->id}", [], $this->headers);

        $response->assertStatus(200);

        $response = $this->getJson("/api/loans/{$loan->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_remove_loan_without_logging_in()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

        $response = $this->deleteJson("/api/loans/{$loan->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function it_returns_a_forbidden_error_when_trying_to_remove_loan_by_others()
    {
        $loan = $this->user->loans()->save(factory(Loan::class)->make());

        $response = $this->deleteJson("/api/loans/{$loan->id}", [], $this->headers);

        $response->assertStatus(403);
    }
}
