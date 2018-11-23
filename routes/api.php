<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace' => 'Api'], function () {
    Route::post('user/login', 'AuthController@login');
    Route::post('user', 'AuthController@register');

    Route::get('user', 'UserController@index');
    Route::match(['put', 'patch'], 'user', 'UserController@update');

    Route::resource('loans', 'LoanController', [
        'except' => [
            'create', 'edit'
        ]
    ]);

    Route::post('loans/{loan}/repayments', 'RepaymentController@store');
});
