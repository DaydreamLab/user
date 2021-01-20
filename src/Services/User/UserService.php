<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Events\Add;
use DaydreamLab\User\Events\Modify;
use DaydreamLab\User\Events\Remove;
use DaydreamLab\User\Events\Login;
use DaydreamLab\User\Helpers\UserHelper;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    protected $package = 'User';

    protected $modelName = 'User';

    protected $modelType = 'Base';

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
        } else {
            $user = Auth::guard('api')->user();
        }

        if (!Hash::check($input->old_password, $user->password)) {
            $this->status = 'OldPasswordIncorrect';
            $this->response = null;
            return false;
        }
        else {
            $user->password = bcrypt($input->password);
            if ($user->save()) {
                if ($user->token()) {
                    $user->token()->delete();
                }

                $this->status = 'ChangePasswordSuccess';
                return true;
            }
            else {
                $this->status = 'ChangePasswordFail';
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
            $this->status = 'EmailIsRegistered';
        } else {
            $this->status = 'EmailIsNotRegistered';
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
                    $this->status = 'IsBlock';
                }
                else {
                    $this->repo->update(['last_login_at' => now()], $user);
                    $tokens = $user->tokens()->get();
                    if(!config('daydreamlab.user.multiple_login'))
                    {
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
                }
            } else { // 帳號尚未啟用
                //$user->notify(new RegisteredNotification($user));
                $this->status = 'Unactivated';
            }
        } else {
            $this->status = 'EmailOrPasswordIncorrect';
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
