<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $loggedInUser;

    protected $user;

    protected $headers;

    public function setUp()
    {
        parent::setUp();
        Artisan::call('passport:install');
        $users = factory(User::class)->times(2)->create();

        $this->loggedInUser = $users[0];
        $accessToken = $this->loggedInUser->createToken('TestToken')->accessToken;
        $this->user = $users[1];

        $this->headers = [
            'Authorization' => "Bearer {$accessToken}"
        ];
    }
}
