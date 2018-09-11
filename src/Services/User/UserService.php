<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Helpers\UserHelper;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected $type = 'User';

    public function __construct(UserRepository $repo)
    {
        parent::__construct($repo);
    }


    public function changePassword(Collection $input)
    {
        if ($input->has('id')) {
            $user = $this->find($input->id);
        }
        else {
            $user = Auth::guard('api')->user();
        }

        if (!Hash::check($input->old_password, $user->password)) {
            $this->status = 'USER_OLD_PASSWORD_INCORRECT';
            $this->response = null;
            return false;
        }
        else {
            $user->password = bcrypt($input->password);
            if ($user->save()) {
                if ($user->token()) {
                    $user->token()->delete();
                }

                $this->status = 'USER_CHANGE_PASSWORD_SUCCESS';
                return true;
            }
            else {
                $this->status = 'USER_CHANGE_PASSWORD_FAIL';
                return false;
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
            $this->status = 'USER_EMAIL_IS_REGISTERED';
        }
        else {
            $this->status = 'USER_EMAIL_IS_NOT_REGISTERED';
        }

        return $user;
    }


    public function login(Collection $input)
    {
        $auth = Auth::attempt([
            'email'     => Str::lower($input->email),
            'password'  => $input->password
        ]);
        if ($auth) {
            $user = Auth::user();
            if ($user->activation) { // 帳號已啟用
                if ($user->block) {
                    $this->status = 'USER_IS_BLOCKED';
                }
                else {
                    $this->status = 'USER_LOGIN_SUCCESS';
                    $this->response = UserHelper::getUserLoginData($user);
                }
            } else { // 帳號尚未啟用
                $user->notify(new RegisteredNotification($user));
                $this->status = 'USER_UNACTIVATED';
            }
        } else {
            $this->status = 'USER_EMAIL_OR_PASSWORD_INCORRECT';
        }
    }

    public function logout()
    {
        $user = Auth::guard('api')->user();
        if ($user && $user->token()) {
            $user->token()->delete();
        }
        $this->status = 'USER_LOGOUT_SUCCESS';
    }

}
