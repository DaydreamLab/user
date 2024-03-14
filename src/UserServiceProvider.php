<?php

namespace DaydreamLab\User;

use DaydreamLab\JJAJ\Middlewares\Cors;
use DaydreamLab\JJAJ\Middlewares\RestrictIP;
use DaydreamLab\User\Middlewares\Admin;
use DaydreamLab\User\Middlewares\Expired;
use DaydreamLab\User\Middlewares\SuperUser;
use DaydreamLab\User\Notifications\Channels\BotbonnieChannel;
use DaydreamLab\User\Notifications\Channels\MitakeChannel;
use DaydreamLab\User\Notifications\Channels\TruetelChannel;
use DaydreamLab\User\Notifications\Channels\XsmsChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected $commands = [
        'DaydreamLab\User\Commands\InstallCommand',
        'DaydreamLab\User\Commands\SeedCommand',
        'DaydreamLab\User\Commands\V2\TransformCommand',
        'DaydreamLab\User\Commands\BackHomeCommand',
        'DaydreamLab\User\Commands\V2\TransformCompanyCategoryNoteCommand',
        'DaydreamLab\User\Commands\V2\HandleCompanyCategoryCommand',
        'DaydreamLab\User\Commands\V2\TransformCompanyCommand',
        'DaydreamLab\User\Commands\V2\AssetInstallCommand',
        'DaydreamLab\User\Commands\V2\CompanyApiRenameCommand',
        'DaydreamLab\User\Commands\V2\HandleNewsletterSubscriptionCommand',
        'DaydreamLab\User\Commands\V2\HandleUserValidateCommand',
        'DaydreamLab\User\Commands\Feat\ExportCompanyUsersInstallCommand',
        'DaydreamLab\User\Commands\Hotfix\V2_001\ClearCompanyMembersIsEmptyCommand',
        'DaydreamLab\User\Commands\Hotfix\V2_001\ClearUserWithoutCompanyCommand',
        'DaydreamLab\User\Commands\Hotfix\V2_001\UpdateCompanyPhoneCommand',
        'DaydreamLab\User\Commands\Feat\Crm\CrmSeedingCommand',
        'DaydreamLab\User\Commands\Hotfix\TotpPermission\FixTotpPermissionCommand',
        'DaydreamLab\User\Commands\Feat\OuterEvent\OuterEventInstallCommand',
        'DaydreamLab\User\Commands\Feat\Botbonnie\BotbonnieSeedingCommand',
    ];


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/constants' => config_path('constants/user')], 'user-constants');
        $this->publishes([__DIR__ . '/Configs' => config_path('daydreamlab')], 'user-configs');
        $this->publishes([__DIR__ . '/Configs' => config_path('daydreamlab')], 'user-configs');
        $this->publishes([__DIR__ . '/../resources/views/emails' => resource_path('views/emails')], 'emails-template');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'user');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
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
        $this->app['router']->aliasMiddleware('restrict-ip', RestrictIP::class);

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('mitake', function ($app) {
                return new MitakeChannel();
            });
            $service->extend('xsms', function ($app) {
                return new XsmsChannel();
            });
            $service->extend('truetel', function ($app) {
                return new TruetelChannel();
            });
            $service->extend('line', function ($app) {
                return new BotbonnieChannel();
            });
            $service->extend('facebook', function ($app) {
                return new BotbonnieChannel();
            });
        });

        $this->commands($this->commands);
    }
}
