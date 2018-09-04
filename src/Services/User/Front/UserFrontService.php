<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Role\Role;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Notifications\ResetPasswordNotification;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\User\Services\Social\SocialUserService;
use Carbon\Carbon;
use DaydreamLab\User\Helpers\UserHelper;
use DaydreamLab\User\Models\User\UserRoleMap;
use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\Upload\UploadService;
use DaydreamLab\User\Services\User\UserRoleMapService;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UserFrontService extends UserService
{
    protected $type = 'UserFront';

    protected $socialUserService;

    protected $uploadService;

    protected $passwordResetService;

    protected $userRoleMapService;

    public function __construct(UserFrontRepository $repo,
                                SocialUserService $socialUserService,
                                UploadService $uploadService,
                                PasswordResetService $passwordResetService,
                                UserRoleMapService $userRoleMapService
    )

    {
        $this->socialUserService    = $socialUserService;
        $this->uploadService        = $uploadService;
        $this->passwordResetService = $passwordResetService;
        $this->userRoleMapService   = $userRoleMapService;
        parent::__construct($repo);
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
                $this->status = 'USER_HAS_BEEN_ACTIVATED';
            }
            else {
                $user->activation = 1;
                $user->save();
                $this->status = 'USER_ACTIVATION_SUCCESS';
            }
        }
        else {
            $this->status = 'USER_ACTIVATION_TOKEN_INVALID';
        }
    }


    public function fblogin()
    {
        $fb_user = Socialite::driver('facebook')->fields([
            'name',
            'first_name',
            'last_name',
            'email',
            'gender',
        ])->stateless()->user();

        $social_user = $this->socialUserService->findBy('provider_id', '=', $fb_user->id)->first();
        if ($social_user) {     // 登入
            $user = $this->find($social_user->user_id);
            if ($user) {
                $data = UserHelper::getUserLoginData($user);
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

            if (!$fb_user->offsetExists('email')) {
                $this->status = 'SOCIAL_USER_REGISTER_EMAIL_NECESSARY';
                $this->response = $fb_user->user;
            }

            $social_data['provider_id']    = $fb_user->id;
            $social_data['provider']       = 'facebook';
            $social_data['token']          = $fb_user->token;
            $social_user                   = $this->socialUserService->create($social_data);

            $user['first_name'] = $fb_user->user['first_name'];
            $user['last_name']  = $fb_user->user['last_name'];
            $user['email']      = $fb_user->user['email'];

            $file_name          = $this->uploadService->avatar($fb_user->avatar);
            $user['image']      = asset('storage/users/'.$file_name);
            $user['password']   = bcrypt(str_random('8'));
            $user['activation']      = 1;
            $user['activate_token']  = str_random(64);
            $user['reset_password']  = 1;

            $user = $this->create($user);
            $data = UserHelper::getUserLoginData($user);

            UserRoleMap::create([
                'user_id'   =>  $user->id,
                'role_id'  =>  6
            ]);
            $social_user->user_id = $user->id;
            $social_user->save();

            $this->status = 'SOCIAL_USER_LOGIN_SUCCESS';
            $this->response = $data ;
        }
    }


    public function forgotPasswordTokenValidate($token)
    {
        $reset_token = $this->passwordResetService->findBy('token', '=', $token)->first();
        if ($reset_token) {
            if (Carbon::now() > new Carbon($reset_token->expired_at)) {
                $this->status = 'USER_RESET_PASSWORD_TOKEN_EXPIRED';
                return false;
            }
            else {
                $this->status = 'USER_RESET_PASSWORD_TOKEN_VALID';
                return $reset_token;
            }
        }
        else {
            $this->status = 'USER_RESET_PASSWORD_TOKEN_INVALID';
            return false;
        }
    }



    /**
     * 註冊帳號
     * @param Collection $input
     */
    public function register(Collection $input)
    {
        $exist = $this->checkEmail($input->email);
        if ($exist->count()) {
            return ;
        }

        $password  = $input->password;
        $input->forget('password');
        $input->put('password', bcrypt($password));
        $input->put('activate_token', str_random(48));

        $user      = $this->add($input->toArray());
        if ($user) {
            $guest_role = Role::where('title', 'Guest')->first();
            $this->userRoleMapService->storeKeysMap(Helper::collect(['user_id' => $user->id, 'role_ids'=> [$guest_role->id]]));
            $user->notify(new RegisteredNotification($user));
            $this->status = 'USER_REGISTER_SUCCESS';
        }
        else {
            $this->status = 'USER_REGISTER_FAIL';
        }
    }


    public function resetPassword(Collection $input)
    {
        $token = $this->forgotPasswordTokenValidate($input->token);
        if ($token) {
            $user = $this->findBy('email', '=', $token->email)->first();
            $user->password = bcrypt($input->password);
            if($user->save()){
                $token->reset_at = now();
                $token->save();
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
            $token = $this->passwordResetService->create([
                'email'         => $input->email,
                'token'         => Str::random(128),
                'expired_at'    => Carbon::now()->addHours(3)
            ]);

            Notification::route('mail', $user->email)->notify(new ResetPasswordNotification($user, $token));
            $this->status = 'USER_RESET_PASSWORD_EMAIL_SEND';
        }
        else {
            $this->status = 'USER_NOT_FOUND';
        }
    }
}
