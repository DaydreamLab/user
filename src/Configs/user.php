<?php

return [

    'register'  => [
        'enable'=> 1,
        'mail'  => [
            'template'  => 'default'
        ],
        'groups' => [7]
    ],
    'login' => [
        'enable'    => 1
    ],

    'forget' => [
        'mail' => [
            'template'  => 'default'
        ]
    ],

    'token_expires_in'  => 604800,
    'multiple_login'    => 1,

    'sms' => [
        'channel' => 'mitake',
        'log' => 1,
        'cooldown' => 60, # 冷卻時間
        'expiredMinutes' => 15,  # 驗證碼過期分鐘數
        'env'       => 'local',
        'mitake'    => [
            'username'  => '',
            'password'  => '',

        ],
        'xsms' => [
            'mdn' => '',
            'uid' => '',
            'upass' => '',
        ]
    ],

    'linebot' => [
        'accessToken' => env('LINE_ACCESS_TOKEN'),
        'channelSecret' => env('LINE_CHANNEL_SECRET')
    ]
];
