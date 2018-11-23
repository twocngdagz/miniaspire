<?php

namespace App\Services\Strategies\Repayment;

use App\Loan;
use App\Repayment;

interface RepaymentStrategy
{
    public function execute();
}
