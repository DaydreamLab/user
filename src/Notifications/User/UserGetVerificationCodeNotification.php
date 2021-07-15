<?php

namespace DaydreamLab\User\Notifications\User;

use DaydreamLab\User\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Psy\Command\ShowCommand;

class UserGetVerificationCodeNotification extends BaseNotification
{
    use Queueable;

    protected $category = 'user';

    protected $type = 'getVerificationCode';

    protected $code = '0000';

    protected $view = 'emails.user.GetVerificationCode';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code, $creatorId = null)
    {
        parent::__construct($creatorId);
        $this->code = $code;
    }


    public function defaultSubject()
    {
        return config('app.name').'帳戶安全性驗證碼';
    }


    public function defaultMailContent()
    {
        return '<p>您於'.config('app.name').'帳戶安全性驗證碼為:'.$this->code .
            '，請於'. config('daydreamlab.user.sms.expiredMinutes').'分鐘內使用。</p>';
    }


    public function defaultSmsContent($channelType)
    {
        $str = '您於' . config('app.name') . '帳戶安全性驗證碼為：' . $this->code .
            '，請於'. config('daydreamlab.user.sms.expiredMinutes').'分鐘內使用。';

        return $str;
    }



    public function getMailParams()
    {
        return [
            'content' => $this->content
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return parent::via($notifiable);
    }
}