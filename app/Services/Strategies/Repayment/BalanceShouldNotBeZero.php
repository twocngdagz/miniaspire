<?php

namespace App\Services\Strategies\Repayment;

use App\Loan;
use App\Services\Strategies\Repayment\RepaymentStrategy;

class BalanceShouldNotBeZero implements RepaymentStrategy
{
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function execute()
    {
        if ($this->loan->balance() <= 0) {
            throw new Exception('This loan is been fully paid.');
        }
    }
}
