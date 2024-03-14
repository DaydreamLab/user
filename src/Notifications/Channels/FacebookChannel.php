<?php

namespace DaydreamLab\User\Notifications\Channels;

use Illuminate\Notifications\Notification;

class FacebookChannel extends BotbonnieChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('facebook', $notification)) {
            return false;
        }
        parent::exec($notifiable, $notification, $to);
    }
}
