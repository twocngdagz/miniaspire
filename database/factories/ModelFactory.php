<?php

use App\Loan;
use App\Repayment;
use App\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(Loan::class, function (\Faker\Generator $faker) {

    static $reduce = 999;

    return [
        'name' => $faker->sentence,
        'amount' => $faker->numberBetween($min = 1000, $max = 9000),
        'duration' => $faker->numberBetween($min = 1, $max = 12),
        'fee' => $faker->numberBetween($min = 1000, $max = 9000),
        'rate' => $faker->numberBetween($min = 1, $max = 10),
        'frequency' => $faker->numberBetween($min = 1, $max = 6),
        'created_at' => \Carbon\Carbon::now()
    ];
});

$factory->define(Repayment::class, function (\Faker\Generator $faker) {

    return [
        'amount' => $faker->numberBetween($min = 1000, $max = 9000),
    ];
});
