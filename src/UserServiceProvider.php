<?php

namespace DaydreamLab\User;

use DaydreamLab\JJAJ\Middlewares\Cors;
use DaydreamLab\User\Middlewares\Admin;
use DaydreamLab\User\Middlewares\Expired;
use DaydreamLab\User\Middlewares\SuperUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

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
        $this->publishes([__DIR__. '/Configs' => config_path('daydreamlab')], 'user-configs');
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
        $this->app->register(UserEventServiceProvider::class);
        $this->app['router']->aliasMiddleware('admin', Admin::class);
        $this->app['router']->aliasMiddleware('superuser', SuperUser::class);
        $this->app['router']->aliasMiddleware('expired', Expired::class);
        $this->app['router']->aliasMiddleware('CORS', Cors::class);
        $this->commands($this->commands);
        $this->registerEloquentFactoriesFrom(__DIR__.'/database/factories');

    }


    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}
