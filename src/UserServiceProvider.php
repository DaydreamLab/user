<?php

namespace DaydreamLab\User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        echo 1;
        echo 2;
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
