<?php

namespace DaydreamLab\User\Notifications\Channels;

use DaydreamLab\Dsth\Notifications\DeveloperNotification;
use DaydreamLab\JJAJ\Helpers\ArrayToXml;
use DaydreamLab\User\Models\SmsDebug\SmsDebug;
use DaydreamLab\User\Models\SmsHistory\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class XsmsChannel
{
    protected $client;

    public $baseUrl = 'https://xsms.aptg.com.tw/XSMSAP/api/APIRTFastHttpRequest';

    public $params = [];

    protected $phoneCode = null;

    protected $phone = null;

    public function __construct()
    {
        $this->params = [
            'MDN'   => config('daydreamlab.user.sms.xsms.mdn'),
            'UID'   => config('daydreamlab.user.sms.xsms.uid'),
            'UPASS' => config('daydreamlab.user.sms.xsms.upass'),
        ];
    }


    public function formatPhone($phone)
    {
        $phoneData = explode('-', $phone);
        $this->phoneCode = $phoneData[0];
        $this->phone = $phoneData[1];

        return substr($this->phoneCode, 1) . (int)$this->phone;
    }


    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('xsms', $notification)) {
            return false;
        }

        $to = $this->formatPhone($to);
        $message = $notification->toXsms($notifiable);

        $subjectLen = mb_strlen($message->subject, 'UTF-8');
        $content = [
            'Subject' => $subjectLen >= 20 ? mb_substr($message->subject, 0, 20) : $message->subject,
            'Message' => strip_tags($message->content),
            'MDNList' => [
                [
                    'MSISDN' => $to,
                ]
            ],
            'AutoSplit' => 'L'
        ];

        $xml = ArrayToXml::convertWithoutDeclaration($content, 'Request');
        $this->params['Content'] = $xml;

        if (config('daydreamlab.user.sms.env') == 'local') {
            $sendResult = true;
            $msgId = '';
            $response = [];
        } else {
            $client = new Client();

            $response = $client->post($this->baseUrl, [
                'form_params' => $this->params
            ]);

            $response = $response->getBody()->getContents();
            $arrayResponse = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
            $statusCode = $arrayResponse->Code;
            $sendResult = $arrayResponse->Code == 0 ? 1 : 0;
            $msgId = $arrayResponse->TaskID;
        }

        if (config('daydreamlab.user.sms.log')) {
            $strlen = mb_strlen($message->content, 'UTF-8');
            $data = [
                'notificationId' => isset($notification->notificationId) ? $notification->notificationId : null,
                'phoneCode'     => $this->phoneCode,
                'phone'         => $this->phone,
                'category'      => $message->category,
                'type'          => $message->type,
                'messageId'     => $msgId,
                'message'       => $message->content,
                'messageLength' => $strlen,
                'messageCount'  => ceil($strlen / 70),
                'success'       => $sendResult,
                'responseCode'  => isset($statusCode) ?: '',
                'created_by'    => $message->creatorId
            ];

            $data = array_merge($data, $message->extraFields);
            $history = SmsHistory::create($data);
        }

        if (config('daydreamlab.user.sms.debug')) {
            SmsDebug::create([
                'payload' => $this->params,
                'response' => $response,
                'historyId' => isset($history) ? $history->id : null
            ]);
        }

        return $sendResult;
    }
}
