<?php

namespace App\Services\Strategies\Repayment;

use Exception;
use App\Loan;
use App\Services\Strategies\Repayment\RepaymentStrategy;

class PaymentAmountShouldBeGreaterThanRepayment implements RepaymentStrategy
{
    public function __construct(Loan $loan, $amount)
    {
        $this->loan = $loan;
        $this->amount = $amount;
    }

    public function execute()
    {
        if ($this->amount < $this->loan->repayment()) {
            throw new Exception('The payment amount is insufficient');
        }
    }
}
