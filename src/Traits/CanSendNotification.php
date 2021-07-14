<?php

namespace DaydreamLab\User\Traits;

use Illuminate\Support\Facades\Notification;

trait CanSendNotification
{
    public function sendNotification($channel, $to, $notification)
    {
        Notification::route($channel, $to)->notify($notification);
    }
}
