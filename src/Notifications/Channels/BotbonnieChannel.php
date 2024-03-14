<?php

namespace DaydreamLab\User\Notifications\Channels;

use DaydreamLab\User\Models\SmsDebug\SmsDebug;
use DaydreamLab\User\Models\SmsHistory\SmsHistory;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class BotbonnieChannel
{
    protected $baseUrl = 'https://api.botbonnie.com/v1/api/message/push';


    public function getPostData($to, $messageContent)
    {
        return [
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
                    'text' => $messageContent
                ],
                'category' => '活動課程通知'
            ]
        ];
    }

    public function exec($notifiable, Notification $notification, $to)
    {
        $toChannel = 'to' . Str::ucfirst(Str::lower($to['platform']));
        $message = $notification->$toChannel($notifiable);
        $messageContent = strip_tags($message->content);

        $post = $this->getPostData($to, $messageContent);

        try {
            $response = (new Client())->post($this->baseUrl, $post);
        } catch (\Throwable $t) {
            SmsDebug::create([
                'payload' => $post['json'],
                'response' => $t->getMessage(),
                'historyId' => null
            ]);
        }

        if ($response->getStatusCode() !== 200) {
            SmsDebug::create([
                'payload' => $post['json'],
                'response' => json_decode($response->getBody()->getContents()),
                'historyId' => null
            ]);
        }
    }
}
