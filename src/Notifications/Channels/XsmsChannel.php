<?php

namespace DaydreamLab\User\Notifications\Channels;

use DaydreamLab\User\Models\SmsHistory\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class XsmsChannel
{
    protected $client;

    public $baseUrl = 'https://xsms.aptg.com.tw/XSMSAP/api/APIRTFastRequest';

    public $params = [];

    protected $phoneCode = null;

    protected $phone = null;

    public function __construct()
    {
        $this->params = [
            'MDN'   => config('daydreamlab.user.sms.xsms.mdn'),
            'UID'   => config('daydreamlab.user.sms.xsms.password'),
            'UPASS' => config('daydreamlab.user.sms.xsms.password'),
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
        if (!$to = $notifiable->routeNotificationFor('xsms', $notification)) {
            return false;
        }

        $to = $this->formatPhone($to);
        $message = $notification->toXsms($notifiable);

        $content = [
            'Request' => [
                'Subject' => $message->subject,
                'Message' => $message->content,
                'MDNList' => [
                    [
                        'MDN'       => $to,
                        'Message'   => strip_tags($message->content)
                    ]
                ]
            ]
        ];
        $xml = new \SimpleXMLElement('<root>');

        show($xml->asXML());
        exit();

        if (config('daydreamlab.user.sms.xsms.env') == 'local') {
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

        if (config('daydreamlab.user.sms.log')) {
            $strlen = mb_strlen($message->content, 'UTF-8');
            $data = [
                'phoneCode'     => $this->phoneCode,
                'phone'         => $this->phone,
                'category'      => $message->category,
                'type'          => $message->type,
                'MitakeMsgId'   => $msgId,
                'message'       => $message->content,
                'messageLength' => $strlen,
                'messageCount'  => ceil($strlen / 70),
                'created_by'    => $message->creatorId
            ];

            $data = array_merge($data, $message->extraFields);
            SmsHistory::create($data);
        }

        return $sendResult;
    }


    public function array_to_xml($array, &$xml) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml->addChild($key);
                    $this->array_to_xml($value, $subnode);
                } else {
                    $this->array_to_xml($value, $subnode);
                }
            } else {
                $xml->addChild($key, $value);
            }
        }
    }
}
