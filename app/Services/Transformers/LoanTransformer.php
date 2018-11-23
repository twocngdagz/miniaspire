<?php

namespace App\Services\Transformers;

use App\Services\Transformers\Transformer;

class LoanTransformer extends Transformer
{
    protected $resourceName = 'loan';

    public function transform($data)
    {
        return [
            'name'     => $data['name'],
            'amount'     => (int)$data['amount'],
            'duration'      => (int)$data['duration'],
            'fee'      => (int)$data['fee'],
            'rate'      => (int)$data['rate'],
            'frequency'      => (int)$data['frequency']
        ];
    }
}
