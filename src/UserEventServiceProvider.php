<?php

namespace DaydreamLab\User;

use App\Providers\EventServiceProvider;
use DaydreamLab\User\Listeners\UserEventSubscriber;


class UserEventServiceProvider extends EventServiceProvider
{

    protected $subscribe = [
        UserEventSubscriber::class
    ];


    public function boot()
    {
        parent::boot();
    }
}
