<?php

namespace DaydreamLab\User\Notifications\User;

use DaydreamLab\User\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Psy\Command\ShowCommand;

class UserDataUpdateNotification extends BaseNotification
{
    use Queueable;

    protected $category = 'User';

    protected $type = 'updateData';

    protected $view = 'emails.User.OldUser';

    protected $member;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($member, $creatorId = null)
    {
        parent::__construct($creatorId);
        $this->member = $member;
    }


    public function defaultSubject()
    {
        return '零壹官網會員回娘家';
    }


    public function defaultMailContent()
    {
        return '';
    }


    public function defaultSmsContent($channelType)
    {
        $str = '';

        return $str;
    }



    public function getMailParams()
    {
        return [
            'userName' => $this->member->name,
            'updateLink' => config('app.url').'/member/update/'.$this->member->uuid,
            'lineLink'  => 'https://line.me/R/ti/p/@zeronetech'
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
