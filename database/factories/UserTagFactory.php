<?php

namespace DaydreamLab\User\Database\Factories;

use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserTag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class UserTagFactory extends Factory
{
    use WithFaker;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserTag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'         => $this->faker->text(20),
            'description'   => $this->faker->text(100),
        ];
    }
}