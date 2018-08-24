<?php

namespace DaydreamLab\User\Helpers;

use DaydreamLab\JJAJ\Helpers\Helper;

class UserHelper
{
    public static function getUserLoginData($user)
    {
        $data['token']       = $user->createToken(env('APP_NAME'))->accessToken;
        $data['first_name']  = $user->first_name;
        $data['last_name']   = $user->last_name;
        $data['phone_code']  = $user->phone_code;
        $data['phone']       = $user->phone;
        $data['id']          = $user->id;
        $data['redirect']    = $user->redirect;
        return $data;
    }

}