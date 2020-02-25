<?php

namespace DaydreamLab\User\Helpers;

use DaydreamLab\JJAJ\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User;

class UserHelper
{
    public function getUserLoginData($user)
    {
        if(!config('daydreamlab.user.multiple_login'))
        {
            $user->tokens()->get()->each(function ($token) {
                $token->delete();
            });
        }

        $tokenResult = $user->createToken(env('APP_NAME'));
        $token       = $tokenResult->token;
        $token->expires_at = now()->addSeconds(config('daydreamlab.user.token_expires_in'));
        $token->save();
        $data['token']       = $tokenResult->accessToken;
        $data['first_name']  = $user->first_name;
        $data['last_name']   = $user->last_name;
        if ($user->isAdmin())
        {
            $sort_groups         = $user->groups->sortBy('id');
            $data['id']          = $user->id;
            $data['redirect']    = $sort_groups->first()->redirect;
            $data['groups']      = $sort_groups;
        }

        return (object)$data;
    }


    /**
     * @param $fb_user User
     */
    public function mergeDataFbUserCreate($fb_user)
    {
        $data = [];
        $data['first_name']     = $fb_user->user['first_name'];
        $data['last_name']      = $fb_user->user['last_name'];
        $data['email']          = $fb_user->email;
        $data['nickname']       = $fb_user->nickname;
        $data['avatar']         = $fb_user->avatar;
        $data['password']       = bcrypt(Str::random(16));
        $data['activate_token'] = Str::random(128);
        $data['activation']     = 1;
        $data['redirect']       = '/';

        return $data;
    }


    /**
     * @param $fb_user User
     */
    public function mergeDataFbSocialUserCreate($fb_user, $user_id)
    {
        $data = [];
        $data['provider']     = 'facebook';
        $data['provider_id']  = $fb_user->id;
        $data['user_id']      = $user_id;
        $data['token']        = $fb_user->token;

        return $data;
    }
}