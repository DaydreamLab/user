<?php

namespace DaydreamLab\User\Database\Factories;

use DaydreamLab\User\Models\Password\PasswordReset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class PasswordResetFactory extends Factory
{
    use WithFaker;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PasswordReset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email'         => $this->faker->email,
            'token'         => Str::random(),
            'created_at'    => now()->toDateTimeString(),
            'expired_at'    => now()->addMinutes(15)->toDateTimeString()
        ];
    }
}