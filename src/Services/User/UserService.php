<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Helpers\OtpHelper;
use DaydreamLab\User\Notifications\GetOtpNotification;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Notifications\OldUserResetPasswordNotification;
use DaydreamLab\User\Events\Add;
use DaydreamLab\User\Events\Modify;
use DaydreamLab\User\Events\Remove;
use DaydreamLab\User\Events\Login;
use DaydreamLab\User\Helpers\UserHelper;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserService extends BaseService
{
    protected $package = 'User';

    protected $modelName = 'User';

    protected $modelType = 'Base';

    protected $user = null;

    protected $helper;

    public function __construct(UserRepository $repo)
    {
        parent::__construct($repo);
        $this->helper = new UserHelper();
    }


    public function add(Collection $input)
    {
        $item = parent::add($input);
        $item
            ? event(new Add($item, $this->getServiceName(), $input, $this->user))
            : null;

        return $item;
    }


    public function changePassword(Collection $input)
    {
        $user = $input->get('id')
            ? $this->find($input->get('id'))
            : Auth::guard('api')->user();

        if (!Hash::check($input->get('old_password'), $user->password)) {
            $this->status = 'OldPasswordIncorrect';
            $this->throwResponse($this->status);
        } else {
            $user->password = bcrypt($input->get('password'));
            if ($user->save()) {
                if ($user->token()) {
                    $user->token()->delete();
                }

                $this->status = 'ChangePasswordSuccess';
                return true;
            } else {
                $this->status = 'ChangePasswordFail';
                $this->throwResponse($this->status);
            }
        }
    }


    /**
     * 檢查 email 是否存在
     *
     * @param $email
     * @return User
     */
    public function checkEmail($email)
    {
        $user = $this->findBy('email', '=', $email)->first();
        if ($user) {
            $this->status = 'EmailIsRegistered';
            $this->throwResponse($this->status, null, ['email' => $email]);
        } else {
            $this->status = 'EmailIsNotRegistered';
        }

        return $user;
    }


    public function login(Collection $input)
    {
        $user = $this->findBy('email', '=', $input->get('email'))->first();
        if ($user && $user->activate_token === 'imported_user' && $user->last_reset_at == null) {
            $passwordResetService = app(PasswordResetService::class);
            $token = $passwordResetService->add(collect([
                'email'         => $input->get('email'),
                'token'         => Str::random(128),
                'expired_at'    => Carbon::now()->addHours(3)
            ]));
            $this->status = 'NeedResetPassword';
            $user->notify(new OldUserResetPasswordNotification($user, $token));
            $this->response = [];
            return;
        }

        $auth = Auth::attempt([
            'email'     => Str::lower($input->get('email')),
            'password'  => $input->get('password')
        ]);

        if ($auth && $input->get('code') === null) {
            // 帳密正確，且未帶入驗證碼就產生一個
            OtpHelper::createOtp($user, 6, 900);
            $this->status = 'SendOtpSuccess';
            $this->response = null;
            return;
        }


        $user = Auth::user() ?: null;
        $login = false;
        if (
            $auth
            && ! $user->block
            && $user->activation
            && OtpHelper::verify('OTP', $input->get('code'), $user)
        ) {
            $this->repo->update([
                'login_fail_count' => 0,
                'last_login_at' => now()
            ], $user);
            $tokens = $user->tokens()->get();
            if(!config('daydreamlab.user.multiple_login')) {
                $tokens->each(function ($token) {
                    $token->multipleLogin = 1;
                    $token->save();
                });
            }

            $this->status = $tokens->count()
                ? 'MultipleLoginSuccess'
                : 'LoginSuccess';
            $this->response = $this->helper->getUserLoginData($user);
            $login = true;

            return  $this->response;
        } else {
            if ($user) {
                // 帳號未啟用
                if (! $user->activation) {
                    $user->notify(new RegisteredNotification($user));
                    $this->status = 'Unactivated';
                    $this->throwResponse($this->status, null, $input->only('email'));
                }

                if ($user->block) {
                    $this->status = 'IsBlocked';
                    $this->throwResponse($this->status, null, $input->only('email'));
                }

                $fail_count = $user->login_fail_count+1;
                if ($fail_count >= config('daydreamlab.user.max_login_fail_attempt')) {
                    $this->repo->update([
                        'login_fail_count' => $fail_count,
                        'block' => 1
                    ], $user);
                } else {
                    $this->repo->update([
                        'login_fail_count' => $fail_count,
                    ], $user);
                }
            }

            $this->status = 'EmailOrPasswordIncorrect';
            $this->throwResponse( $this->status);
        }

        event(new Login($this->getServiceName(), $login,  $this->status, $user));

        return $user;
    }


    public function logout()
    {
        $user = Auth::guard('api')->user();
        if ($user && $user->token()) {
            $user->token()->delete();
        }
        $this->status = 'LogoutSuccess';
    }


    public function modify(Collection $input)
    {
        $result =  parent::modify($input);

        //event(new Modify($this->find($input->get('id')), $this->getServiceName(), $result, $input, $this->user));

        return $result;
    }


    public function remove(Collection $input)
    {
        $result =  parent::remove($input);

        event(new Remove($this->getServiceName(), $result, $input, $this->user));

        return $result;
    }
}
