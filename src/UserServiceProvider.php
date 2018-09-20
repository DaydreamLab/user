<?php

namespace DaydreamLab\User;

use DaydreamLab\JJAJ\Exceptions\BaseExceptionHandler;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Middlewares\Admin;
use DaydreamLab\User\Middlewares\Expired;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Debug\ExceptionHandler;
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
        $this->app->bind(
            ExceptionHandler::class,
            BaseExceptionHandler::class
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('admin', Admin::class);
        $this->app['router']->aliasMiddleware('expired', Expired::class);

        $this->commands($this->commands);
    }
}
