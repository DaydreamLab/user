<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Notifications\ResetPasswordNotification;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\User\Services\Social\SocialUserService;
use Carbon\Carbon;
use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class UserFrontService extends UserService
{
    use LoggedIn;

    protected $modelType = 'Front';

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
                $this->throwResponse('HasBeenActivated', null, ['token' => $token]);
            } else {
                $user->activation = 1;
                $user->save();
                $this->status = 'ActivationSuccess';
            }
        } else {
            $this->status = 'ActivationTokenInvalid';
            $this->throwResponse($this->status, null, ['token' => $token]);
        }
    }


    public function addMapping($item, $input)
    {
        $item->groups()->attach(config('daydreamlab.user.register.groups'));
    }


    public function fbLogin()
    {
        $fb_user = Socialite::driver('facebook')
            ->fields(['name', 'first_name', 'last_name', 'email', 'gender'])
            ->stateless()
            ->user();

        $social_user = $this->socialUserService->findByChain(
            ['provider', 'provider_id'],
            ['=', '='],
            ['facebook',$fb_user->id]
        )->first();
        if ($social_user) {     // 登入
            $user = $this->find($social_user->user_id);
            if ($user) {
                // 更新 token
                $social_user->token = $fb_user->token;
                $social_user->save();
                $this->status = 'FbLoginSuccess';
                $this->response = $this->helper->getUserLoginData($user) ;
            } else {
                $this->status = 'FbRegisterUnfinished';
                $this->response = null;
            }
        } else {
            return $this->fbRegister($fb_user);
        }

        return $social_user;
    }


    /**
     * @param $user User
     */
    public function fbRegister($fb_user)
    {
        if (!$fb_user->email) {
            $this->status = 'FbEmailRequired';
            $this->response = ['access_token' => $fb_user->token];
            return false;
        }

        if ($this->checkEmail($fb_user->email)) {
            return false;
        }

        $user_data  = $this->helper->mergeDataFbUserCreate($fb_user);
        $user       = $this->add($user_data);

        $social_data = $this->helper->mergeDataFbSocialUserCreate($fb_user, $user->id);
        $socialUser  = $this->socialUserService->store($social_data);

        $this->status = 'FbRegisterSuccess';
        $this->response = $this->helper->getUserLoginData($user) ;

        return $user;
    }


    public function fbLoginComplete(Collection $input)
    {
        $fb_user = Socialite::driver('facebook')->fields(['name', 'first_name', 'last_name', 'email', 'gender'])
            ->userFromToken($input->get('access_token'));

        $social_user = $this->socialUserService->findByChain(
            ['provider', 'provider_id'],
            ['=', '='],
            ['facebook',$fb_user->id]
        )->first();
        if ($social_user) {     // 登入
            $user = $this->find($social_user->user_id);
            if ($user) {
                // 更新 token
                $social_user->token = $fb_user->token;
                $social_user->save();
                $this->status = 'FbLoginSuccess';
                $this->response = $this->helper->getUserLoginData($user) ;
            } else {
                $this->status = 'FbRegisterUnfinished';
                $this->response = null;
            }
        } else {
            $fb_user->email = $input->get('email');
            return $this->fbRegister($fb_user);
        }

        return $social_user;
    }


    public function forgotPasswordTokenValidate($token)
    {
        $reset_token = $this->passwordResetService->findBy('token', '=', $token)->first();
        if ($reset_token) {
            if (Carbon::now() > Carbon::parse($reset_token->expired_at)) {
                $this->status = 'ResetPasswordTokenExpired';
                $this->throwResponse($this->status, null, ['token' => $token]);
            } elseif ($reset_token->reset_at) {
                $this->status = 'ResetPasswordTokenIsUsed';
                $this->throwResponse($this->status, null, ['token' => $token]);
            } else {
                $this->status = 'ResetPasswordTokenValid';
            }
        } else {
            $this->status = 'ResetPasswordTokenInvalid';
            $this->throwResponse( $this->status, null, ['token' => $token]);
        }

        return $reset_token;
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

            $user = $this->add($input);
            $user->notify(new RegisteredNotification($user));
            $this->status = 'RegisterSuccess';
            $this->response = $user->refresh();
        } else {
            $this->status = 'RegistrationIsBlocked';
            $this->throwResponse($this->status, null, $input);
        }

        return $this->response;
    }


    public function resetPassword(Collection $input)
    {
        $token = $this->forgotPasswordTokenValidate($input->get('token'));

        $user = $this->findBy('email', '=', $token->email)->first();
        $data = [
            'password' => bcrypt($input->get('password')),
            'last_reset_at' => now()->toDateTimeString()
        ];

        $this->passwordResetService->update(['reset_at' => now()->toDateTimeString()], $token);

        $this->update($data, $user);
        $user->tokens()->delete();
        $this->status = 'ResetPasswordSuccess';
    }


    public function sendResetLinkEmail(Collection $input)
    {
        $user = $this->findBy('email', '=', $input->get('email'))->first();
        if ($user) {
            $token = $this->passwordResetService->add(collect([
                'email'         => $input->get('email'),
                'token'         => Str::random(128),
                'expired_at'    => Carbon::now()->addHours(3)
            ]));

            $user->notify(new ResetPasswordNotification($user, $token));
            $this->status = 'ResetPasswordEmailSend';
        } else {
            $this->status = 'ItemNotExist';
            $this->throwResponse('ItemNotExist', null, $input);
        }
    }


    public function editProfile(Collection $input)
    {
        if ($input->has('password')) {
            $password  = $input->get('password');
            $input->forget('password');
            $input->put('password', bcrypt($password));
        }

        if ($input->has('email')) {
            $exist = $this->checkEmail($input->get('email'));
            if ($exist) {
                return ;
            }
        }

        $input->put('id', $this->user->id);
        $update = parent::store($input);

        if ($update) {
            $this->response = $this->find($this->user->id)->refresh();
        }

        return $this->response;
    }
}
