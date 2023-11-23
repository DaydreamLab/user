<?php

namespace DaydreamLab\User\Notifications\Channels;

use DaydreamLab\Dsth\Notifications\DeveloperNotification;
use DaydreamLab\JJAJ\Helpers\ArrayToXml;
use DaydreamLab\User\Models\SmsDebug\SmsDebug;
use DaydreamLab\User\Models\SmsHistory\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class TruetelChannel
{
    protected $client;

    public $baseUrl = '';

    public $params = [];

    protected $phoneCode = null;

    protected $phone = null;

    public function __construct()
    {
        $ip = config('daydreamlab.user.sms.truetel.host');
        $port = config('daydreamlab.user.sms.truetel.port');
        $this->baseUrl = "http://{$ip}:{$port}/mpushapi/smssubmit";
        $this->params = [
            'SysId' => config('daydreamlab.user.sms.truetel.sysid'),
            'SrcAddress' => config('daydreamlab.user.sms.truetel.source_address')
        ];
    }


    public function getMessageCount($length)
    {
        if ($length <= 70) {
            return 1;
        } else {
            $q = (int) ($length / 67);
            $r = $length % 67;
            return $r <= 3 ? $q : $q + 1;
        }
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
        if (!$to = $notifiable->routeNotificationFor('truetel', $notification)) {
            return false;
        }

        $to = $this->formatPhone($to);
        $message = $notification->toTruetel($notifiable);
        $messageContent = strip_tags($message->content);
        $messageLength = mb_strlen($messageContent, 'UTF-8');
        $this->params['LongSmsFlag'] = $messageLength > 70;
        $this->params['SmsBody'] = base64_encode($messageContent);
        $this->params['DestAddress'] = $to;

        if (config('daydreamlab.user.sms.env') == 'local') {
            $sendResult = true;
            $msgId = '';
            $response = [];
        } else {
            $client = new Client();

            $postData = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'xml' => ArrayToXml::convert(
                        $this->params,
                        'SmsSubmitReq',
                        true,
                        'UTF-8',
                        '1.0',
                        [],
                        null,
                        false
                    )
                ]
            ];

            $response = $client->post($this->baseUrl, $postData);
            try {
                $response = $response->getBody()->getContents();
            } catch (\Throwable $t) {
                SmsDebug::create([
                    'payload' => $this->params,
                    'response' => $response->getBody(),
                    'historyId' => null
                ]);
            }
            $arrayResponse = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
            $statusCode = $arrayResponse->ResultCode;
            $sendResult = $arrayResponse->ResultText;
            $msgId = $arrayResponse->MessageId;
        }


        if (config('daydreamlab.user.sms.log')) {
            $data = [
                'notificationId' => isset($notification->notificationId) ? $notification->notificationId : null,
                'phoneCode'     => $this->phoneCode,
                'phone'         => $this->phone,
                'category'      => $message->category,
                'type'          => $message->type,
                'messageId'     => $msgId,
                'message'       => $message->content,
                'messageLength' => $messageLength,
                'messageCount'  => $this->getMessageCount($messageLength),
                'success'       => $statusCode == '00000' ? 1 : 0,
                'responseCode'  => $statusCode ?? '',
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
