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
        $messageLength = mb_strlen($messageContent, 'UTF-8');

        $post = $this->getPostData($to, $messageContent);

        if (config('daydreamlab.user.sms.env') == 'local') {
            $sendResult = true;
            $msgId = '';
            $response = [];
        } else {
            $response = (new Client())->post($this->baseUrl, $post);
            try {
                $response_content = json_decode($response->getBody()->getContents());
            } catch (\Throwable $t) {
                SmsDebug::create([
                    'payload' => $post['json'],
                    'response' => $response->getBody(),
                    'historyId' => null
                ]);
            }
            $statusCode = $response->getStatusCode();
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
                'success'       => $sendResult ? 1: 0,
                'responseCode'  => $statusCode ?? '',
                'created_by'    => $message->creatorId
            ];

            $data = array_merge($data, $message->extraFields);
            $history = SmsHistory::create($data);
        }

        if (config('daydreamlab.user.sms.debug')) {
            SmsDebug::create([
                'payload' => $post['json'],
                'response' => $sendResult,
                'historyId' => isset($history) ? $history->id : null
            ]);
        }

        return $sendResult;
    }
}
