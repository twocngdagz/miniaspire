<?php

namespace App\Services\Tranformers;

use App\Services\Transformers\Transformer;

class UserTransformer extends Transformer
{
    protected $resourceName = 'user';

    public function transform($data)
    {
        return [
            'email'     => $data['email'],
            'token'     => $data['token'],
            'name'      => $data['name']
        ];
    }
}
