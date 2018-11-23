<?php

namespace App\Http\Requests\Api;

use App\Http\Request\Api\ApiRequest;

class UpdateLoan extends ApiRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->get('loan') ?: [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $loan = $this->route('loan');

        return $loan->user_id == auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
