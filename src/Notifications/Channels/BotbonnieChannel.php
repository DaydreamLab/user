<?php

namespace DaydreamLab\User\Notifications\Channels;

use DaydreamLab\User\Models\SmsDebug\SmsDebug;
use DaydreamLab\User\Models\SmsHistory\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class BotbonnieChannel
{
    protected $client;

    public $baseUrl = 'https://api.botbonnie.com/v1/api/message/push';

    public array $post = [];

    public function __construct()
    {
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$to = (($notifiable->routeNotificationFor('line', $notification))
            ?:($notifiable->routeNotificationFor('facebook', $notification)) )) {
            return false;
        }

        $message = $notification->toBotbonnie($notifiable);
        $messageContent = strip_tags($message->content);
        $messageLength = mb_strlen($messageContent, 'UTF-8');
        $post = [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.botbonnie_token'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'bot_id' => config('app.botbonnie_bot_id'),
                'bot_raw_uid' => $to['botbonnie_user_id'],
                'bot_pid' => $to['page_id'],
                'bot_channel' => $to['platform'] == 'LINE' ? 1 : 0,
                'message' => [
                    'type' => 'text',
                    'text' => '測試'
                ],
                'category' => '活動課程通知'
            ]
        ];

        show($post);
        if (config('daydreamlab.user.sms.env') == 'local') {
            $sendResult = true;
            $msgId = '';
            $response = [];
        } else {
            $response =  (new Client())->post($this->baseUrl, $post);
            try {
                $response_content = $response->getBody()->getContents();
            } catch (\Throwable $t) {
                SmsDebug::create([
                    'payload' => $post['json'],
                    'response' => $response->getBody(),
                    'historyId' => null
                ]);
            }
            $statusCode = $response->getStatusCode();
            show($response->getBody()->getContents());
            $sendResult = $response_content->res;
            $msgId = '';
        }


        if (config('daydreamlab.user.sms.log')) {
            $data = [
                'notificationId' => $notification->notificationId ?? null,
                'phoneCode'     => '',
                'phone'         => '',
                'category'      => $message->category,
                'type'          => $message->type,
                'messageId'     => $msgId,
                'message'       => $message->content,
                'messageLength' => $messageLength,
                'messageCount'  => 0,
                'success'       => $sendResult ?: 1,
                'responseCode'  => $statusCode ?? '',
                'created_by'    => $message->creatorId
            ];

            $data = array_merge($data, $message->extraFields);
            $history = SmsHistory::create($data);
        }

        if (config('daydreamlab.user.sms.debug')) {
            SmsDebug::create([
                'payload' => $post['json'],
                'response' => $response,
                'historyId' => isset($history) ? $history->id : null
            ]);
        }

        return $sendResult;
    }
}
