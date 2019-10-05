<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Events\Add;
use DaydreamLab\User\Events\Modify;
use DaydreamLab\User\Events\Remove;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Events\Login;
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

    protected $helper;


    public function __construct(UserRepository $repo)
    {
        parent::__construct($repo);
        $this->helper = new UserHelper();
    }

    public function add(Collection $input)
    {
        $item = parent::add($input);

        event(new Add($item, $this->getServiceName(), $input, $this->user));

        return $item;
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

        $user = Auth::user() ?: null;
        $login = false;
        if ($auth) {
            if ($user->activation) { // 帳號已啟用
                if ($user->block) {
                    $this->status = 'USER_IS_BLOCKED';
                }
                else {
                    $this->repo->update(['last_login_at' => now()], $user);
                    $this->status = 'USER_LOGIN_SUCCESS';
                    $this->response = $this->helper->getUserLoginData($user);
                    $login = true;
                }
            } else { // 帳號尚未啟用
                //$user->notify(new RegisteredNotification($user));
                $this->status = 'USER_UNACTIVATED';
                return false;
            }
        } else {
            $this->status = 'USER_EMAIL_OR_PASSWORD_INCORRECT';
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
        $this->status = 'USER_LOGOUT_SUCCESS';
    }


    public function modify(Collection $input)
    {
        $result =  parent::modify($input);

        event(new Modify($this->find($input->get('id')), $this->getServiceName(), $result, $input, $this->user));

        return $result;
    }


    public function remove(Collection $input)
    {
        $result =  parent::remove($input);

        event(new Remove($this->getServiceName(), $result, $input, $this->user));

        return $result;
    }

}
