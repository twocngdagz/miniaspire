<?php

namespace App\Http\Request\Api;

class RegisterUser extends ApiRequest
{
    protected function validationData()
    {
        return $this->get('user') ?: [];
    }

    public function rules()
    {
        return [
            'name' => 'required|max:50|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
        ];
    }
}
