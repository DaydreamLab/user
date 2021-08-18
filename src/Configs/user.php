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
        'mitake'    => [
            'username'  => '',
            'password'  => '',
            'env'       => 'local'
        ],
        'xsms' => [
            'MDN' => '',
            'UID' => '',
            'UPASS' => '',
        ]
    ],

    'linebot' => [
        'accessToken' => env('LINE_ACCESS_TOKEN'),
        'channelSecret' => env('LINE_CHANNEL_SECRET')
    ]
];
