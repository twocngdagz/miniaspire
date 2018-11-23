<?php

namespace App\Http\Request\Api;

class LoginUser extends ApiRequest
{
    protected function validationData()
    {
        return $this->get('user') ?: [];
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ];
    }
}
