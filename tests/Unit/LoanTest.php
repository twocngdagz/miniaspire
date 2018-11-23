<?php

namespace Tests\Unit;

use App\Loan;
use App\Repayment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_should_return_the_total_repayments_of_a_loan()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make());

        $loan->repayments()->saveMany(factory(Repayment::class)->times(3)->make(["amount" => 50]));

        $this->assertEquals($loan->totalRepayments(), 150);
    }

    /** @test */
    public function it_should_compute_the_correct_repayment()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make(["amount" => 600, "rate" => 5, "duration" => 12]));

        $this->assertEquals(round($loan->repayment(), 2), 51.36);
    }

    /** @test */
    public function it_should_compute_the_correct_balance()
    {
        $loan = $this->loggedInUser->loans()->save(factory(Loan::class)->make(["amount" => 600, "rate" => 5, "duration" => 12]));

        $loan->repayments()->saveMany(factory(Repayment::class)->times(3)->make(["amount" => $loan->repayment()]));

        $this->assertEquals(452.79, $loan->balance());
    }
}
