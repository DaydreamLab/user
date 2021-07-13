<?php

namespace DaydreamLab\User;

use DaydreamLab\JJAJ\Middlewares\Cors;
use DaydreamLab\User\Middlewares\Admin;
use DaydreamLab\User\Middlewares\Expired;
use DaydreamLab\User\Middlewares\SuperUser;
use DaydreamLab\User\Notifications\Channels\MitakeChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;



class UserServiceProvider extends ServiceProvider
{
    protected $commands = [
        'DaydreamLab\User\Commands\InstallCommand',
        'DaydreamLab\User\Commands\SeedCommand',
    ];


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/constants' => config_path('constants/user')], 'user-configs');
        $this->publishes([__DIR__ . '/Configs' => config_path('daydreamlab')], 'user-configs');
        $this->publishes([__DIR__ . '/Configs' => config_path('daydreamlab')], 'user-configs');
        $this->publishes([__DIR__ . '/../resources/views/emails' => resource_path('views/emails')], 'emails-template');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'user');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
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

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('mitake', function ($app) {
                return new MitakeChannel();
            });
        });

        $this->commands($this->commands);
    }
}
