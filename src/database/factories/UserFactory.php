<?php
use Faker\Generator as Faker;


$factory->define(\DaydreamLab\User\Models\User\User::class, function (Faker $faker) {

    $gender = $faker->randomElement(['male', 'female']);
    return [
        'email'         => $faker->email,
        'password'      => bcrypt(1234),
        'first_name'    => $faker->firstName($gender),
        'last_name'     => $faker->lastName,
        'nickname'      => $faker->userName,
        'gender'        => $gender,
        'image'         => $faker->image(),
        'birthday'      => $faker->date(),
        //'activate_token'=> 'activate_token'
    ];
});