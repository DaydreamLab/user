<?php

return [
    'ACCESS_DENIED'                     => 403,
    'ACTIVATION_SUCCESS'                => 200,
    'ACTIVATION_TOKEN_INVALID'          => 403,

    'BLOCK_SUCCESS'                     => 200,
    'BLOCK_FAIL'                        => 500,

    'CHANGE_PASSWORD_SUCCESS'           => 200,
    'CHANGE_PASSWORD_FAIL'              => 500,

    'EMAIL_IS_REGISTERED'               => 403,
    'EMAIL_IS_NOT_REGISTERED'           => 200,
    'EMAIL_OR_PASSWORD_INCORRECT'       => 403,

    'FB_EMAIL_IS_REQUIRED'              => 403,
    'FB_LOGIN_SUCCESS'                  => 200,
    'FB_REGISTER_SUCCESS'               => 200,
    'FB_REGISTER_UNFINISHED'            => 403,

    'GET_SELF_PAGE_SUCCESS'             => 200,

    'HAS_BEEN_ACTIVATED'                => 403,

    'IS_BLOCKED'                        => 403,
    'INSUFFICIENT_PERMISSION_ASSIGN_GROUP'=> 403,

    'LOGIN_SUCCESS'                     => 200,
    'LOGIN_FAIL'                        => 500,
    'LOGIN_IS_BLOCKED'                  => 403,
    'LOGOUT_SUCCESS'                    => 200,

    'MULTIPLE_LOGIN_SUCCESS'            => 200,

    'OLD_PASSWORD_INCORRECT'            => 403,

    'REGISTER_SUCCESS'                  => 200,
    'REGISTRATION_IS_BLOCKED'           => 403,
    'RESET_PASSWORD_SUCCESS'            => 200,
    'RESET_PASSWORD_FAIL'               => 500,
    'RESET_PASSWORD_EMAIL_SEND'         => 200,
    'RESET_PASSWORD_TOKEN_VALID'        => 200,
    'RESET_PASSWORD_TOKEN_INVALID'      => 200,
    'RESET_PASSWORD_TOKEN_IS_USED'      => 403,
    'RESET_PASSWORD_TOKEN_EXPIRED'      => 403,

    'NEED_RESET_PASSWORD'               => 200,

    'TOKEN_EXPIRED'                     => 401,
    'TOKEN_REVOKED'                     => 403,

    'UNAUTHORIZED'                      => 403,
    'UNACTIVATED'                       => 403,
];