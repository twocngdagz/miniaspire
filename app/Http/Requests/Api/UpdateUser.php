<?php

namespace App\Http\Requests\Api;

use App\Http\Request\Api\ApiRequest;

class UpdateUser extends ApiRequest
{
    protected function validationData()
    {
        return $this->get('user') ?: [];
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|max:50|unique:users,name,' . $this->user()->id,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|min:6',
        ];
    }
}
