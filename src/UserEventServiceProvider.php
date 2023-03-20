<?php

namespace DaydreamLab\User;

use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Listeners\UpdateCompanyUsersUserGroupAndEdm;
use DaydreamLab\User\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class UserEventServiceProvider extends EventServiceProvider
{
    protected $subscribe = [
        //UserEventSubscriber::class
    ];


    protected $listen = [
        UpdateCompanyUsersUserGroupAndEdmEvent::class => [
            UpdateCompanyUsersUserGroupAndEdm::class
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
