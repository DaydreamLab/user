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
        $str = '零壹官網會員回娘家，立即點擊連結：';

        $fullUrl = config('app.url').'/member/update/'.$this->member->uuid;

        $client = new Client();
        $uri = config('app.env') == 'production'
            ? 'https://dsth.me/shortcode.php'
            : 'https://demo.dsth.me/shortcode.php';
        $response = $client->request('POST', $uri, [
            'form_params' => [
                'url' =>$fullUrl,
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
                    ->notify(new DeveloperNotification('[零壹官網] 處理自動短網址發生例外',
                        '短網址：' . $fullUrl,
                        $response->getBody()->getContents()
                    ));
                throw new InternalServerErrorException('GetShortCodeError', null);
            }
        } else {
            Notification::route('mail', 'technique@daydream-lab.com')
                ->notify(new DeveloperNotification('[零壹官網] 處理自動短網址發生例外',
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
