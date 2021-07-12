<?php

namespace DaydreamLab\User;

use DaydreamLab\User\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

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
