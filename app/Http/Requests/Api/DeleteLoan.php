<?php

namespace App\Http\Requests\Api;

use App\Http\Request\Api\ApiRequest;

class DeleteLoan extends ApiRequest
{
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
}
