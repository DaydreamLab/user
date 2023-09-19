<?php

namespace DaydreamLab\User\Helpers;

use GuzzleHttp\Client;

class BotbonnieHelper
{
    public static function getTag($tagId)
    {
        $response = (new Client())->get(
            'https://api.botbonnie.com/v1/api/tag/' . $tagId,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.botbonnie_token'),
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        return json_decode($response->getBody()->getContents())->res;
    }

    public static function getUsers($next = null)
    {
        $query = [
            'botId' => 'bot-XasmCsjzY',
            'limit' => 3086
        ];

        if ($next) {
            $query['next'] = $next;
        }

        $response = (new Client())->get(
            'https://api.botbonnie.com/v1/api/bot/customers',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.botbonnie_token'),
                    'Content-Type' => 'application/json'
                ],
                'query' => $query
            ]
        );

        return json_decode($response->getBody()->getContents())->res;
    }


    public static function getAllUsers()
    {
        $data = self::getUsers();
        $users = $data->records;
        while ($data->next) {
            $data = self::getUsers($data->next);
            $users = array_merge($users, $data->records);
        }

        return $users;
    }


    public static function getTags($users)
    {
        $tags = [];
        foreach ($users as $user) {
            foreach ($user->tags as $userTag) {
                if (!array_key_exists($userTag->id, $tags)) {
                    $tags[$userTag->id] = BotbonnieHelper::getTag($userTag->id);
                    $tags[$userTag->id]->users = [];
                } else {
                    $tags[$userTag->id]->users[] = $user;
                }
            }
        }

        return $tags;
    }
}
