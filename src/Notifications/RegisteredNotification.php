<?php

namespace DaydreamLab\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    protected $subject = '';
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


    public function getContent()
    {
        $content = '感謝您加入零壹會員，您的帳號已正式啟用。';
        if ($this->user->isDealer && $this->user->companyEmailIsDealer) {
            $content .= '<br>因您的公司具有經銷商資格，請點擊下方連結進行驗證。';
            $this->subject = '[零壹官網] 帳號啟用驗證';
        } else {
            $this->subject = '[零壹官網] 帳號已啟用';
        }

        return $content;
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
        # 這邊要注意裡面有偷塞 subject 因此要先 call
        $content = $this->getContent();

        return $template == 'default' ?
                (new MailMessage())
                    ->line('The introduction to the notification.')
                    ->action('Activate your account', url($path))
                    ->line('Thank you for using our application!')
            :   (new MailMessage())
                    ->subject($this->subject)
                    ->view($template, [
                        'user' => $this->user,
                        'subject' => $this->subject,
                        'content' => $content,
                        'clickType' => 'dealerValidate',
                        'clickUrl'  => $this->user->dealerValidateUrl,
                        'order' => null
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
