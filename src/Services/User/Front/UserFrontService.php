<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Notifications\ResetPasswordNotification;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\User\Services\Social\SocialUserService;
use Carbon\Carbon;
use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class UserFrontService extends UserService
{
    protected $type = 'UserFront';

    protected $socialUserService;

    protected $passwordResetService;

    public function __construct(
        UserFrontRepository     $repo,
        SocialUserService       $socialUserService,
        PasswordResetService    $passwordResetService
    )
    {
        parent::__construct($repo);
        $this->socialUserService    = $socialUserService;
        $this->passwordResetService = $passwordResetService;
    }


    /**
     * 啟用帳號
     *
     * @param $token
     */
    public function activate($token)
    {
        $user = $this->findBy('activate_token', '=', $token)->first();
        if ($user) {
            if ($user->activation) {
                $this->status = 'HasBeenActivated';
            } else {
                $user->activation = 1;
                $user->save();
                $this->status = 'ActivationSuccess';
            }
        } else {
            $this->status = 'ActivationTokenInvalid';
        }
    }


    public function create($data)
    {
        $user = parent::create($data);
        if ($user) {
            $user->groups()->attach(config('daydreamlab.user.register.groups'));
        }

        return $user;
    }


    public function fbLogin()
    {
        $fb_user = Socialite::driver('facebook')
            ->fields([
                'name',
                'first_name',
                'last_name',
                'email',
                ])
            ->stateless()->user();

        $social_user = $this->socialUserService->findBy('provider_id', '=', $fb_user->id)->first();
        if ($social_user) {     // 登入
            $user = $this->find($social_user->user_id);
            if ($user) {
                $data = $this->helper->getUserLoginData($user);
                // 更新 token
                $social_user->token = $fb_user->token;
                $social_user->save();
                $this->status = 'SOCIAL_USER_LOGIN_SUCCESS';
                $this->response = $data ;
            }
            else {
                $this->status = 'SOCIAL_USER_REGISTER_NOT_COMPLETE';
                $this->response = $fb_user->user;
            }
        }
        else {                  //註冊
            return $this->fbRegister($fb_user);
        }

        return $social_user;
    }


    /**
     * @param $user User
     */
    public function fbRegister($fb_user)
    {
        if (!$fb_user->offsetExists('email')) {
            $this->status = 'SOCIAL_USER_REGISTER_EMAIL_REQUIRED';
            return false;
        }

        if ($this->checkEmail($fb_user->email))
        {
            return false;
        }

        $user_data  = $this->helper->mergeDataFbUserCreate($fb_user);
        $user       = $this->create($user_data);
        if (!$user) {
            $this->status = 'USER_CREATE_FAIL';
            return false;
        }

        $social_data = $this->helper->mergeDataFbSocialUserCreate($fb_user, $user->id);
        $social      = $this->socialUserService->create($social_data);
        if (!$social)
        {
            $this->status = 'SOCIAL_USER_CREATE_FAIL';
            return false;
        }

        $this->status = 'SOCIAL_USER_LOGIN_SUCCESS';
        $this->response = $this->helper->getUserLoginData($user) ;

        return $user;
    }


    public function forgotPasswordTokenValidate($token)
    {
        $reset_token = $this->passwordResetService->findBy('token', '=', $token)->first();
        if ($reset_token) {
            if (Carbon::now() > new Carbon($reset_token->expired_at)) {
                $this->status = 'ResetPasswordTokenExpired';
                return false;
            } elseif ($reset_token->reset_at) {
                $this->status = 'ResetPasswordTokenIsUsed';
            } else {
                $this->status = 'ResetPasswordTokenValid';
                return $reset_token;
            }
        } else {
            $this->status = 'ResetPasswordTokenInvalid';
            return false;
        }
    }



    /**
     * 註冊帳號
     * @param Collection $input
     */
    public function register(Collection $input)
    {
        if (config('daydreamlab.user.register.enable')) {
            $exist = $this->checkEmail($input->get('email'));
            if ($exist) {
                return ;
            }

            $password  = $input->get('password');
            $input->forget('password');
            $input->put('password', bcrypt($password));
            $input->put('activate_token', Str::random(48));

            $user      = $this->add($input);
            $user->groups()->attach(config('daydreamlab.user.register.groups'));
            if ($user) {
                $user->notify(new RegisteredNotification($user));
                $this->status = 'RegisterSuccess';
            } else {
                $this->status = 'RegisterFail';
            }
        } else {
            $this->status = 'RegistrationIsBlocked';
        }
    }


    public function resetPassword(Collection $input)
    {
        $token = $this->forgotPasswordTokenValidate($input->token);
        if ($token) {
            $user = $this->findBy('email', '=', $token->email)->first();
            $user->password = bcrypt($input->password);
            $user->last_reset_at = now();
            if($user->save()){
                $token->delete();
                $this->status = 'USER_RESET_PASSWORD_SUCCESS';
            }
            else{
                $this->status = 'USER_RESET_PASSWORD_FAIL';
            }
        }
    }


    public function sendResetLinkEmail(Collection $input)
    {
        $user = $this->findBy('email', '=', $input->email)->first();
        if ($user) {
            $token = $this->passwordResetService->add(collect([
                'email'         => $input->email,
                'token'         => Str::random(128),
                'expired_at'    => Carbon::now()->addHours(3)
            ]));

            Notification::route('mail', $user->email)->notify(new ResetPasswordNotification($user, $token));
            $this->status = 'ResetPasswordEmailSend';
        } else {
            $this->status = 'ItemNotExist';
        }
    }
}
