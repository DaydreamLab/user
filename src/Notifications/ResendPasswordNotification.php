<?php

namespace DaydreamLab\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResendPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $password;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
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
        $template = config('daydreamlab-user.resendpwd.mail.template');

        return $template == 'default' ? (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('If you did not request a password reset, no further action is required.')
            : (new MailMessage)->view($template, ['user' => $this->user, 'password' => $this->password]);
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
