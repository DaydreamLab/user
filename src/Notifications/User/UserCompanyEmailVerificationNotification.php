<?php

namespace DaydreamLab\User\Notifications\User;

use DaydreamLab\User\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;

class UserCompanyEmailVerificationNotification extends BaseNotification
{
    use Queueable;

    protected $category = 'User';

    protected $type = 'companyEmailValidation';

    protected $user;

    protected $view = 'emails.User.CompanyEmailValidation';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $creatorId = null)
    {
        parent::__construct($creatorId);
        $this->user = $user;
        $this->onQueue('batch-job');
    }


    public function defaultSubject()
    {
        return '[' . config('app.name') . ']' . '經銷商資格郵件驗證';
    }


    public function defaultMailContent()
    {
        return "因您的公司具有經銷商資格，請點擊下方連結進行驗證。";
    }


    public function defaultSmsContent($channelType)
    {
        return '';
    }



    public function getMailParams()
    {
        return [
            'subject'   => $this->defaultSubject(),
            'content'   => $this->defaultMailContent(),
            'user'      => $this->user,
            'clickType' => 'dealerValidate',
            'clickUrl'  => $this->user->dealerValidateUrl
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
