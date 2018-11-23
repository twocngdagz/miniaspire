<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Request\Api\LoginUser;
use App\Http\Request\Api\RegisterUser;
use App\Services\Tranformers\UserTransformer;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function login(LoginUser $request)
    {
        $credentials = $request->only('user.email', 'user.password');
        $credentials = $credentials['user'];

        if (! Auth::once($credentials)) {
            return $this->respondFailedLogin();
        }

        return $this->respondWithTransformer(auth()->user());
    }

    public function register(RegisterUser $request)
    {
        $user = User::create([
            'name' => $request->input('user.name'),
            'email' => $request->input('user.email'),
            'password' => $request->input('user.password'),
        ]);

        return $this->respondWithTransformer($user);
    }
}
