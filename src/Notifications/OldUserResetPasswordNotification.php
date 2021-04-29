<?php

namespace DaydreamLab\User\Notifications;

use DaydreamLab\JJAJ\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OldUserResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *'user_name'             => 'required|string',
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $template = config('daydreamlab.user.oldforget.mail.template');

        $url = 'olduser/password/reset/' . $this->token->token;

        return (new MailMessage)
                ->subject( '通訊大賽 舊帳號密碼重設')
                ->view($template, ['user' => $this->user, 'url' => url($url)]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
