<?php

namespace DaydreamLab\User\Services\User;

use Carbon\Carbon;
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
use DaydreamLab\User\Models\User;

class UserService extends BaseService
{
    protected $type = 'User';

    protected $model_name = 'User';

    protected $helper;


    public function __construct(UserRepository $repo)
    {
        parent::__construct($repo);
        $this->helper = new UserHelper();
    }

    public function add(Collection $input)
    {
        $this->canAction('add');

        $item = parent::add($input);

        event(new Add($item, $this->model_name, $input, $this->user));

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
        $user = $this->repo->findBy('email', '=', $input->email)->first();

        if ($user) {
            if ($user->failed_login_at && now()->diffInMinutes($user->failed_login_at) < 5) {
                if ($user->failed_login_count >= 3) {
                    $this->status = 'OVER_3TIMES';
                    return $user;
                }
            } else {
                $this->repo->update([
                    'failed_login_count' => 0,
                    'failed_login_at' => now()
                ], $user);
            }
        }

        if ($auth) {
            if ($user->activation) { // 帳號已啟用
                if ($user->block) {
                    $this->status = 'USER_IS_BLOCKED';
                }
                else {
                    $this->repo->update([
                        'failed_login_count' => 0,
                        'failed_login_at' => null
                    ], $user);
                    $this->status = 'USER_LOGIN_SUCCESS';
                    $this->response = $this->helper->getUserLoginData($user);
                    $login = true;
                }
            } else { // 帳號尚未啟用
//                $user->notify(new RegisteredNotification($user));
                $this->status = 'USER_UNACTIVATED';
                return false;
            }
        } else {
            if ($user) {
                $this->repo->update([
                    'failed_login_count' => $user->failed_login_count + 1
                ], $user);
            }
            $this->status = 'USER_EMAIL_OR_PASSWORD_INCORRECT';
        }

        event(new Login($this->model_name, $login,  $this->status, $user));

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


    public function modify(Collection $input, $diff = false)
    {
        $result =  parent::modify($input, $diff);

        event(new Modify($this->find($input->get('id')), $this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function remove(Collection $input, $diff = false)
    {
        $result =  parent::remove($input, $diff);

        event(new Remove($this->model_name, $result, $input, $this->user));

        return $result;
    }

}
