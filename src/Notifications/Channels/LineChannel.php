<?php

namespace DaydreamLab\User\Notifications\Channels;

use Illuminate\Notifications\Notification;

class LineChannel extends BotbonnieChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('line', $notification)) {
            return false;
        }
        parent::exec($notifiable, $notification, $to);
    }
}
