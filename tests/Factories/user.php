<?php

use Faker\Generator as Faker;
use Marshmallow\Attributes\Tests\Stubs\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => 'foobarbaz',
    ];
});
