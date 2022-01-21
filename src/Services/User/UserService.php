<?php

namespace DaydreamLab\User\Services\User;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use DaydreamLab\User\Events\Add;
use DaydreamLab\User\Events\Modify;
use DaydreamLab\User\Events\Remove;
use DaydreamLab\User\Events\Login;
use DaydreamLab\User\Helpers\UserHelper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            ? event(new Add($item, $this->getServiceName(), $input->except('password'), $this->user))
            : null;

        return $item;
    }


    public function changePassword(Collection $input)
    {
        $user = $input->get('id')
            ? $this->find($input->get('id'))
            : Auth::guard('api')->user();

        if (!Hash::check($input->get('old_password'), $user->password)) {
            throw new ForbiddenException('OldPasswordIncorrect');
        } else {
            $this->repo->modify($user, collect([
                'password' => $input->get('password')
            ]));

            $user->token()
                ? $user->tokens()->delete()
                : null;
            $this->status = 'ChangePasswordSuccess';
        }

        return $user;
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
            throw new ForbiddenException('EmailIsRegistered', ['email' => $email]);
        }
        $this->status = 'EmailIsNotRegistered';
        $this->response = $user;

        return $this->response;
    }


    public function login(Collection $input)
    {
        if ($input->get('email')) {
            $v_users = User::where('email', $input->get('email'))->get();
            $auth = false;
            $provider = Auth::createUserProvider('users');
            foreach ($v_users as $v_user) {
                $auth = $provider->validateCredentials($v_user, [
                    'password'  => $input->get('password')
                ]);
                if ($auth) {
                    Auth::login($v_user);
                    break;
                }
            }
//            $auth = Auth::attempt([
//                'email'     => Str::lower($input->get('email')),
//                'password'  => $input->get('password')
//            ]);

            if (!$auth) {
                throw new ForbiddenException('EmailOrPasswordIncorrect');
            }
            $user = Auth::user();
            if ($user->block) {
                throw new ForbiddenException('IsBlocked');
            }
        } else {
            $user = $this->findBy('mobilePhone', '=', $input->get('mobilePhone'))->first();
            if (!$user) {
                throw new NotFoundException('ItemNotExist', ['mobilePhone' => $input->get('mobilePhone')]);
            }

            $verify = Hash::check($input->get('verificationCode'), $user->verificationCode);
            if ($verify) {
                if (config('app.env') == 'production') {
                    if (now() > Carbon::parse($user->lastSendAt)->addMinutes(config('daydreamlab.user.sms.expiredTime'))) {
                        throw new ForbiddenException('VerificationCodeExpired');
                    }
                }
                $this->status = 'VerifyVerificationCodeSuccess';
            } else {
                throw new ForbiddenException('InvalidVerificationCode');
            }
        }

        # 只送過驗證碼，但是沒有執行過完整流程
        if (!$user->activation) {
            if ( !$user->email || !$user->name || in_array($user->activateToken, ['couponAddUser', 'eventAddUser'])) {
                $this->status = 'RegistrationIsNotCompleted';
                $this->response = $user;
                return $this->response;
            } elseif ($user->activateToken == 'importedUser') {
                $this->status = 'OldUserNeedToCompleteData';
                $this->response = $user;
                return $this->response;
            } else {
                throw new ForbiddenException('VerificationPending');
            }
        }
/*
        if ($user->block) {
            throw new ForbiddenException('IsBlocked');
        }
*/
        $this->repo->modify($user, collect([
            'verificationCode' => bcrypt(Str::random()),
            'lastLoginAt' => now(),
            'lastLoginIp' => $input->get('lastLoginIp')
        ]));
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

        $tokenResult = $user->createToken(config('app.name'));
        $token = $tokenResult->token;
        $token->expires_at = now()->addSeconds(config('daydreamlab.user.token_expires_in'));
        $token->save();
        $user->accessToken = $tokenResult->accessToken;
        $this->response = $user;
        $login = true;

        # 如果有帶 line account link token
        if ($lineLinkToken = $input->get('lineLinkToken')) {
            $nonce = $this->createNonce();
            $freshUser = $user->fresh();
            $freshUser->line_nonce = $nonce;
            $freshUser->save();
            $this->response['lineAccountLinkRedirectUrl'] = "https://access.line.me/dialog/bot/accountLink?linkToken=" . $lineLinkToken . "&nonce=" . $nonce;
        }

        event(new Login($this->getServiceName(), $login,  $this->status, $user));

        return $this->response;
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


    private function createNonce($bits = 128)
    {
        $bytes = ceil($bits / 8);
        $return = '';
        for ($i = 0; $i < $bytes; $i++) {
            $return .= chr(mt_rand(0, 255));
        }
        return base64_encode($return);
    }
}
