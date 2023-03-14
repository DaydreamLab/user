<?php

namespace DaydreamLab\User\Database\Factories;

use DaydreamLab\User\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    use WithFaker;
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
        $gender = $this->faker->randomElement(['male', 'female']);
        return [
            'email'         => $this->faker->email,
            'first_name'    => $this->faker->firstName($gender),
            'last_name'     => $this->faker->lastName,
            'user_name'     => $this->faker->userName,
            'nickname'      => $this->faker->userName,
            'gender'        => $gender,
            'image'         => $this->faker->image(),
            'birthday'      => $this->faker->date(),
            'activate_token'=> Str::random()
        ];
    }
}