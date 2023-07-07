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

    public $baseUrl = 'http://IP:PORT/mpsiweb/smssubmit';

    public $params = [];

    protected $phoneCode = null;

    protected $phone = null;

    public function __construct()
    {
        $this->params = [
            'SysId' => 'API帳號代碼',
            'SrcAddress' => '來源位址'
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
        if (!$to = $notifiable->routeNotificationFor('truetel', $notification)) {
            return false;
        }

        $to = $this->formatPhone($to);
        $message = $notification->toTruetel($notifiable);
        $messageContent = strip_tags($message->content);
        $messageLength = mb_strlen($messageContent, 'UTF-8');
        if ($messageLength > 70) {
            $this->params['LongSmsFlag'] = true;
        }
        $this->params['SmsBody'] = base64_encode($messageContent);
        $this->params['DestAddress'] = $to;

        if (config('daydreamlab.user.sms.env') == 'local') {
            $sendResult = true;
            $msgId = '';
            $response = [];
        } else {
            $client = new Client();
            $sendResult = true;
            $xml = ArrayToXml::convert($this->params, 'SmsSubmitReq');
            show($xml);
//            $response = $client->post($this->baseUrl, [
//                'form_params' => $xml
//            ]);

//            $response = $response->getBody()->getContents();
//            $arrayResponse = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
//            $statusCode = $arrayResponse->ResultCode;
//            $sendResult = $arrayResponse->ResultText;
//            $msgId = $arrayResponse->MessageId;
        }

//        if (config('daydreamlab.user.sms.log')) {
//            $data = [
//                'notificationId'=> isset($notification->notificationId) ? $notification->notificationId : null,
//                'phoneCode'     => $this->phoneCode,
//                'phone'         => $this->phone,
//                'category'      => $message->category,
//                'type'          => $message->type,
//                'messageId'     => $msgId,
//                'message'       => $message->content,
//                'messageLength' => $messageLength,
//                'messageCount'  => $messageLength <= 70 ? 1 : ceil($messageLength / 67),
//                'success'       => $sendResult,
//                'responseCode'  => isset($statusCode) ?: '',
//                'created_by'    => $message->creatorId
//            ];
//
//            $data = array_merge($data, $message->extraFields);
//            $history = SmsHistory::create($data);
//        }
//
//        if (config('daydreamlab.user.sms.debug')) {
//            SmsDebug::create([
//                'payload' => $this->params,
//                'response' => $response,
//                'historyId' => isset($history) ? $history->id : null
//            ]);
//        }

        return $sendResult;
    }
}
