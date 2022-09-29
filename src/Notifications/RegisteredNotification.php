<?php

namespace DaydreamLab\User\Notifications;

use DaydreamLab\User\Helpers\CompanyHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $path = '/user/activate/' . $this->user->activateToken;

        $template = config('daydreamlab.user.register.mail.template');

        $dealerValidateUrl = null;
        if (
            $this->user->company
            && $this->user->company->company
            && $this->user->company->company->category
            && in_array($this->user->company->company->category->title, ['經銷會員', '零壹員工'])
            && CompanyHelper::checkEmailIsDealer($this->user->company->email, $this->user->company->company)
        ) {
            $dealerValidateUrl = config('app.url') . '/dealer/validate/' . $this->user->company->validateToken;
        }

        return $template == 'default' ?
                (new MailMessage())
                    ->line('The introduction to the notification.')
                    ->action('Activate your account', url($path))
                    ->line('Thank you for using our application!')
            :   (new MailMessage())
                    ->subject('[零壹科技] 帳號已啟用')
                    ->view($template, [
                        'user' => $this->user,
                        'subject' => '[零壹科技] 帳號已啟用',
                        'dealerValidateUrl' => $dealerValidateUrl
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
