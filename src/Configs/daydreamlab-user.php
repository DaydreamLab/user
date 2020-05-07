<?php

return [

    'register'  => [
        'enable'=> 1,
        'mail'  => [
            'template'  => 'default'
        ],
        'group' => 4
    ],
    'resendpwd' => [
        'mail' => [
            'resend_template' => 'emails.users.resendpwd',
            'new_template' => 'emails.users.newpwd'
        ]
    ],
    'activate' => [
        'mail' => [
            'template' => 'default'
        ]
    ]

];