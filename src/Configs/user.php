<?php

return [

    'register'  => [
        'enable'=> 1,
        'mail'  => [
            'template'  => 'default'
        ],
        'groups' => [4]
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
        'channel' => \DaydreamLab\User\Notifications\Channels\MitakeChannel::class,
        'log'       => 1,
        'mitake'    => [
            'username'  => '',
            'password'  => '',
            'env'       => 'local'
        ]
    ]
];