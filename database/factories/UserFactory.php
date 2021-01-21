<?php

namespace DaydreamLab\User\Database\Factories;

use DaydreamLab\User\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
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
    }
}