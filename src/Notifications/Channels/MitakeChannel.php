<?php

namespace DaydreamLab\User\Notifications\Channels;

use DaydreamLab\User\Models\Sms\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class MitakeChannel
{
    protected $client;

    public $baseUrl = 'http://smsapi.mitake.com.tw/api/mtk/SmSend?CharsetURL=UTF8';

    public $params = [];

    protected $phoneCode = null;

    protected $phone = null;

    public function __construct()
    {
        $this->params = [
            'username' => config('daydreamlab.user.sms.mitake.username'),
            'password' => config('daydreamlab.user.sms.mitake.password'),
        ];
    }


    public function formatPhone($phone)
    {
        $phoneData = explode('-', $phone);
        $this->phoneCode = $phoneData[0];
        $this->phone = $phoneData[1];

        return substr($this->phoneCode, 1). (int)$this->phone;
    }


    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor(MitakeChannel::class, $notification)) {
            return false;
        }

        $to = $this->formatPhone($to);
        $message = $notification->toMitake($notifiable);

        $this->params['smbody'] = strip_tags($message->content);
        $this->params['dstaddr'] = $to;

        if (config('daydreamlab.user.mitake.env') == 'local') {
            $sendResult = true;
            $msgId = '';
        } else {
            $client = new Client();
            $response = $client->post($this->baseUrl, [
                'form_params' => $this->params
            ]);

            $content = $response->getBody()->getContents();

            $contentExplode = explode('%0D%0A', urlencode($content));

            /**
             * 0: client id
             * 1: message id
             * 2: status code
             * 3: account point
             * 4: null
             */
            $statusCode = explode('=', urldecode($contentExplode[2]))[1];
            $msgId      = explode('=', urldecode($contentExplode[1]))[1];
            $sendResult = in_array($statusCode, [1,2,4]) ? 1 : 0;
        }


        if (config('app.mitake.log')) {
            $strlen = mb_strlen($message->content, 'UTF-8');
            $data = [
                'phoneCode'     => $this->phoneCode,
                'phone'         => $this->phone,
                'type'          => $message->msgType,
                'MitakeMsgId'   => $msgId,
                'message'       => $message->content,
                'messageLength' => $strlen,
                'messageCount'  => ceil($strlen / 70),
                'created_by'    => $message->creatorId
            ];

            $data = array_merge($data, $message->extrafields);
            SmsHistory::create($data);
        }

        return $sendResult;
    }
}