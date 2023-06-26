<?php

return [
    'ACCESS_DENIED'                     => 403,
    'ACTIVATION_SUCCESS'                => 200,
    'ACTIVATION_TOKEN_INVALID'          => 403,

    'BATCH_UPDATE_SUCCESS'              => 200,
    'BLOCK_SUCCESS'                     => 200,
    'BLOCK_FAIL'                        => 500,

    'CHANGE_PASSWORD_SUCCESS'           => 200,
    'CHANGE_PASSWORD_FAIL'              => 500,
    'COMPANY_VAT_EMPTY'                 => 403,

    'DEALER_VALIDATE_SUCCESS'           => 200,

    'EMAIL_IS_REGISTERED'               => 403,
    'EMAIL_IS_NOT_REGISTERED'           => 200,
    'EMAIL_OR_PASSWORD_INCORRECT'       => 403,

    'FB_EMAIL_IS_REQUIRED'              => 403,
    'FB_LOGIN_SUCCESS'                  => 200,
    'FB_REGISTER_SUCCESS'               => 200,
    'FB_REGISTER_UNFINISHED'            => 403,

    'GET_SELF_PAGE_SUCCESS'             => 200,

    'HAS_BEEN_ACTIVATED'                => 403,

    'INVALID_DEALER_TOKEN'              => 403,
    'IS_BLOCKED'                        => 403,
    'IMPORT_PROCESSING'                 => 200,
    'INSUFFICIENT_PERMISSION_ASSIGN_GROUP' => 403,
    'INVALID_VERIFICATION_CODE'         => 403,
    'IP_REJECTED'                       => 403,

    'LOGIN_SUCCESS'                     => 200,
    'LOGIN_FAIL'                        => 500,
    'LOGIN_IS_BLOCKED'                  => 403,
    'LOGOUT_SUCCESS'                    => 200,

    'MULTIPLE_LOGIN_SUCCESS'            => 200,
    'MOBILE_PHONE_EXIST'                => 403,
    'MOBILE_PHONE_NOT_EXIST'            => 200,
    'MOBILE_PHONE_EMAIL_NOT_MATCH'      => 403,

    'OLD_PASSWORD_INCORRECT'            => 403,
    'OLD_USER_NEED_TO_COMPLETE_DATA'    => 403,

    'PASSWORD_SAME_AS_PREVIOUS'         => 403,

    'REGISTER_SUCCESS'                  => 200,
    'REGISTER_FAIL'                     => 500,
    'REGISTRATION_IS_NOT_COMPLETED'     => 403,
    'REGISTRATION_IS_BLOCKED'           => 403,
    'RESET_PASSWORD_SUCCESS'            => 200,
    'RESET_PASSWORD_FAIL'               => 500,
    'RESET_PASSWORD_EMAIL_SEND'         => 200,
    'RESET_PASSWORD_TOKEN_VALID'        => 200,
    'RESET_PASSWORD_TOKEN_INVALID'      => 200,
    'RESET_PASSWORD_TOKEN_IS_USED'      => 403,
    'RESET_PASSWORD_TOKEN_EXPIRED'      => 403,

    'SEND_VERIFICATION_CODE_SUCCESS'    => 200,
    'SEND_VERIFICATION_CODE_IN_COOL_DOWN' => 200,
    'SEND_DEALER_VALIDATE_EMAIL_SUCCESS' => 200,
    'SEND_DEALER_VALIDATE_EMAIL_FAIL' => 403,

    'TOKEN_EXPIRED'                     => 401,
    'TOKEN_REVOKED'                     => 403,

    'UNAUTHORIZED'                      => 401,
    'UNACTIVATED'                       => 403,

    'VERIFY_VERIFICATION_CODE_SUCCESS'  => 200,
    'VERIFICATION_CODE_EXPIRED'         => 403,
    'VERIFICATION_PENDING'              => 403,

    'LINE_BIND_SUCCESS' => 200,
    'LINE_BIND_DUPLICATE' => 200,

    // 二階段驗證
    'SEND_TOTP_SECRET_SUCCESS' => 200,
    'SEND_OTP_SUCCESS'    => 200,
    'TOTP_CODE_INCORRECT' => 403,
    'OTP_CODE_INCORRECT' => 403,
    'CHECK_SUCCESS' => 200,
    'TOTP_SECRET_EXPIRED' => 400,
];
