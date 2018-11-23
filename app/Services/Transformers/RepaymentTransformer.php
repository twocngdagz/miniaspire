<?php

namespace App\Services\Transformers;

use App\Services\Transformers\Transformer;

class RepaymentTransformer extends Transformer
{
    protected $resourceName = 'repayment';

    public function transform($data)
    {
        return [
            'amount'     => (int)$data['amount'],
        ];
    }
}
