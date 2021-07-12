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
        'log'       => 0,
        'mitake'    => [
            'username'  => '',
            'password'  => '',
            'env'       => 'local'
        ]
    ]
];