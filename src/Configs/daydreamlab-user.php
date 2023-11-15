<?php

return [

    'register'  => [
        'enable'=> 1,
        'mail'  => [
            'template'  => 'default'
        ],
        'group' => 4
    ],

    // days
    'reset_password_duration' => env('RESET_PASSWORD_DURATION', null)
];
