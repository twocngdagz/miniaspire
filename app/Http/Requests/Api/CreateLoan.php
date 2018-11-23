<?php

namespace App\Http\Requests\Api;

use App\Http\Request\Api\ApiRequest;

class CreateLoan extends ApiRequest
{
    protected function validationData()
    {
        return $this->get('loan') ?: [];
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'amount' => 'required',
            'duration' => 'required',
            'fee' => 'required',
            'rate' => 'required',
            'frequency' => 'required'
        ];
    }
}
