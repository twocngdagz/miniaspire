<?php

namespace App\Services\Strategies;

use App\Services\Strategies\Repayment\RepaymentStrategy;

class StrategyManager
{
    protected $strategies = [];

    public function add(RepaymentStrategy $strategy)
    {
        $this->strategies[] = $strategy;
    }

    public function execute()
    {
        foreach ($this->strategies as $strategy) {
            return $strategy->execute();
        }
    }
}
