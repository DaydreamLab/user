<?php

namespace DaydreamLab\User;

use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Middlewares\Admin;
use DaydreamLab\User\Middlewares\Expired;
use Illuminate\Auth\AuthenticationException;
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

//        $this->app->singleton(AuthenticationException::class, function (AuthenticationException $e){
//            return ResponseHelper::response('USER_UNAUTHORIZED', $e->getMessage());
//        });
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
