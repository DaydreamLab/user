<?php

namespace DaydreamLab\User\Traits;

use Illuminate\Support\Facades\Notification;

trait CanSendNotification
{
    public function sendNotification($channelType, $to, $notification)
    {
        if ($channelType == 'sms') {
            Notification::route(config('daydreamlab.user.sms.channel'), $to)->notify($notification);
        } elseif ($channelType == 'mail') {
            Notification::route('mail', $to)->notify($notification);
        }
    }
}
