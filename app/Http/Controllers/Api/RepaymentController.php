<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\CreateRepayment;
use App\Loan;
use App\Services\Strategies\Repayment\BalanceShouldNotBeZero;
use App\Services\Strategies\Repayment\PaymentAmountShouldBeGreaterThanRepayment;
use App\Services\Strategies\StrategyManager;
use App\Services\Transformers\RepaymentTransformer;

class RepaymentController extends ApiController
{
    public function __construct(RepaymentTransformer $transformer, StrategyManager $strategy)
    {
        $this->transformer = $transformer;
        $this->strategy = $strategy;

        $this->middleware('auth:api');
    }

    public function store(CreateRepayment $request, Loan $loan)
    {
        $amount = $request->input('repayment.amount');

        $this->strategy->add(new PaymentAmountShouldBeGreaterThanRepayment($loan, $amount));
        $this->strategy->add(new BalanceShouldNotBeZero($loan));
        $this->executeStrategies();

        $repayment = $loan->repayments()->create([
            'amount' => $amount
        ]);

        return $this->respondWithTransformer($repayment);
    }
}
