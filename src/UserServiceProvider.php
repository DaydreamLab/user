<?php

namespace DaydreamLab\User;

use DaydreamLab\User\Middlewares\Admin;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{


    protected $commands = [
        'DaydreamLab\User\Commands\InstallCommand',
    ];
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__. '/constants' => config_path('constants')], 'user-configs');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        include __DIR__. '/routes/api.php';
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('DaydreamLab\User\Controllers\User\Front\UserFrontController');
        $this->app['router']->aliasMiddleware('admin', Admin::class);
        $this->commands($this->commands);
    }
}
