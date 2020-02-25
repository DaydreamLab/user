<?php

return [

    'register'  => [
        'enable'=> 1,
        'mail'  => [
            'template'  => 'default'
        ],
        'groups' => [4]
    ],


    'forget' => [
        'mail' => [
            'template'  => 'emails.Merchant.MerchantForgetPassword'
        ]
    ],

    'token_expires_in'  => 604800,
    'multiple_login'    => 1,
];