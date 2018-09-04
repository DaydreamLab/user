<?php

namespace DaydreamLab\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Middlewares\Admin;
use DaydreamLab\User\Middlewares\Expired;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        $this->app['router']->aliasMiddleware('expired', Expired::class);
        $this->commands($this->commands);
    }
}
