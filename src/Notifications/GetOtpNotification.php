<?php

namespace DaydreamLab\User\Notifications;

use DaydreamLab\JJAJ\Helpers\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GetOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    protected $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
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
        $template = 'emails.User.GetOtp';
        
        return (new MailMessage)
                ->subject( '[通訊大賽] 登入保護驗證')
                ->view($template, [
                    'email' => $this->user->email,
                    'code' => $this->code,
                ]);

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
