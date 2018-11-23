<?php

namespace Tests\Feature\Api;

use App\Loan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoanReadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_returns_an_empty_array_of_articles_when_no_loan_exist()
    {
        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
            ->assertJson([
                'loans' => [],
                'loansCount' => 0
            ]);
    }

    /** @test */
    public function it_returns_the_loans_and_correct_total_loans_count()
    {
        $loans = $this->user->loans()->saveMany(factory(Loan::class)->times(2)->make());

        $response = $this->getJson('/api/loans');

        $response->assertStatus(200)
            ->assertJson([
                'loans' => [
                    [
                        'name' => $loans[0]->name,
                        'amount' => $loans[0]->amount,
                        'duration' => $loans[0]->duration,
                        'fee' => $loans[0]->fee,
                        'rate' => $loans[0]->rate,
                        'frequency' => $loans[0]->frequency,
                    ],
                    [
                        'name' => $loans[1]->name,
                        'amount' => $loans[1]->amount,
                        'duration' => $loans[1]->duration,
                        'fee' => $loans[1]->fee,
                        'rate' => $loans[1]->rate,
                        'frequency' => $loans[1]->frequency,
                    ]
                ],
                'loansCount' => 2
            ]);
    }

    /** @test */
    public function it_returns_the_loan_by_id_if_valid_and_not_found_error_if_invalid()
    {
        $loan = $this->user->loans()->save(factory(Loan::class)->make());

        $response = $this->getJson("/api/loans/{$loan->id}");

        $response->assertStatus(200)
            ->assertJson([
                'loan' => [
                    'name' => $loan->name,
                    'amount' => $loan->amount,
                    'duration' => $loan->duration,
                    'fee' => $loan->fee,
                    'rate' => $loan->rate,
                    'frequency' => $loan->frequency,
                ]
            ]);

        $response = $this->getJson('/api/loans/randomid');

        $response->assertStatus(404);
    }
}
