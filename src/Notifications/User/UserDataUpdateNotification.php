<?php

namespace DaydreamLab\User\Notifications\User;

use DaydreamLab\Dsth\Notifications\DeveloperNotification;
use DaydreamLab\JJAJ\Exceptions\InternalServerErrorException;
use DaydreamLab\User\Notifications\BaseNotification;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Notification;

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
        $this->onQueue('update-userdata-job');
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
        $str = '您好, 零壹官網全新上線, 因會員機制更新, 我們邀請您回娘家更新資料，以便能即時收到最新產品與活動課程資訊。若有疑問，請洽詢02-26560777或marketing@zerone.com.tw，謝謝。立即前往：';

        $fullUrl = config('app.url') . '/member/update/' . $this->member->uuid . '?back=1';

        $client = new Client();
        $uri = config('app.env') == 'production'
            ? 'https://dsth.me/shortcode.php'
            : 'https://demo.dsth.me/shortcode.php';
        $response = $client->request('POST', $uri, [
            'form_params' => [
                'url' => $fullUrl,
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody()->getContents());
            $shortCode = $data->code;
        } elseif ($response->getStatusCode() == 303) {
            $uri = config('app.env') == 'production'
                ? 'https://dsth.me/shorten.php'
                : 'https://demo.dsth.me/shorten.php';

            $response = $client->request('POST', $uri, [
                'form_params' => [
                    'url' => $fullUrl,
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents());
                $shortCode = $data->code;
            } else {
                Notification::route('mail', 'technique@daydream-lab.com')
                    ->notify(new DeveloperNotification(
                        '[零壹官網] 處理自動短網址發生例外',
                        '短網址：' . $fullUrl,
                        $response->getBody()->getContents()
                    ));
                throw new InternalServerErrorException('GetShortCodeError', null);
            }
        } else {
            Notification::route('mail', 'technique@daydream-lab.com')
                ->notify(new DeveloperNotification(
                    '[零壹官網] 處理自動短網址發生例外',
                    '短網址：' . $fullUrl,
                    $response->getBody()->getContents()
                ));
            throw new InternalServerErrorException('GetShortCodeError', null);
        }

        $shortUrl = config('app.env') == 'production'
            ? 'dsth.me/' . $shortCode
            : 'demo.dsth.me/' . $shortCode;

        $str .= $shortUrl;

        return $str;
    }


    public function getMailParams()
    {
        return [
            'userName' => $this->member->name,
            'updateLink' => config('app.url') . '/member/update/' . $this->member->uuid . '?backHome=1',
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
