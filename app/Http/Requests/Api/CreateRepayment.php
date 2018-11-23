<?php

namespace App\Http\Requests\Api;

use App\Http\Request\Api\ApiRequest;

class CreateRepayment extends ApiRequest
{
    protected function validationData()
    {
        return $this->get('repayment') ?: [];
    }

    public function rules()
    {
        return [
            'amount' => 'required',
        ];
    }

    public function authorize()
    {
        $loan = $this->route('loan');

        return $loan->user_id == auth()->id();
    }
}
