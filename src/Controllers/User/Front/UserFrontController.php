<?php

namespace DaydreamLab\User\Controllers\User\Front;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Requests\User\Front\UserFrontChangePasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontCheckEmailPost;
use DaydreamLab\User\Requests\User\Front\UserFrontForgetPasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontLoginPost;
use DaydreamLab\User\Requests\User\Front\UserFrontRegisterPost;
use DaydreamLab\User\Requests\User\Front\UserFrontResetPasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontGetUserPost;
use DaydreamLab\User\Requests\User\Front\UserFrontEditPost;
use DaydreamLab\User\Requests\User\Front\UserFrontFBLoginPost;
use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontGetLoginResource;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontLoginResource;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontResource;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use DaydreamLab\User\Requests\User\Front\UserFrontStorePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Passport\ApiTokenCookieFactory;
use Laravel\Socialite\Facades\Socialite;

class UserFrontController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'User';

    protected $modelType = 'Front';

    public function __construct(UserFrontService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function activate($token)
    {
        $this->service->activate($token);

        return $this->response($this->service->status, $this->service->response);
    }


    public function checkEmail(UserFrontCheckEmailPost $request)
    {
        $this->service->checkEmail($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function forgetchangePassword(UserFrontChangePasswordPost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->changePassword($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function fbLogin()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }


    public function fbCallback(UserFrontFBLoginPost $request)
    {
        $this->service->fbLogin($request->validated());

        if ($this->service->status == 'FbEmailRequired') {
            return $this->response($this->service->status, $this->service->response);
        }

        return $this->response($this->service->status,
            gettype($this->service->response) == 'object'
            ? new UserFrontLoginResource($this->service->response)
            : $this->service->response
        );
    }


    public function forgotPasswordTokenValidate($token)
    {
        $this->service->forgotPasswordTokenValidate($token);

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItem(UserFrontGetUserPost $request)
    {
        $this->service->status = 'GetItemSuccess';

        return $this->response($this->service->status,  new UserFrontResource($request->user('api')));
    }



    public function getLogin(Request $request)
    {
        $user = Auth::guard('api')->authenticate();
        if ($user) {
            $token = $user->token();
            if (Carbon::parse($token->expires_at)->diffInDays(now()) < 3) {
                $token->expires_at  = now()->addSeconds(config('daydreamlab.user.token_expires_in'));
                $token->save();
            }
            $status = 'GetItemSuccess';
            $response = $user;
            $response->token = $request->bearerToken();
        } else {
            $status = 'TokenExpired';
            $response = null;
        }

        return $this->response($status,  new UserFrontGetLoginResource($response));
    }


    public function login(UserFrontLoginPost $request)
    {
        if(config('daydreamlab.user.login.enable')) {
            $this->service->login($request->validated());
            if ($this->service->status == 'NeedResetPassword') {
                return $this->response($this->service->status, $this->service->response);
            }
        } else {
            $this->service->status = 'LoginIsBlocked';
            $this->service->response = null;
        }

        $response = $this->response($this->service->status,
            $this->service->response
            ? new UserFrontLoginResource($this->service->response)
            : null
        );

        if (in_array($this->service->status, ['LoginSuccess', 'MultipleLoginSuccess'])) {
            $response = $response->cookie('laravel_session', Str::random(128));
        }

        return $response;
    }


    public function register(UserFrontRegisterPost $request)
    {
        if (config('daydreamlab.user.register.enable')) {
            $this->service->register($request->validated());
        } else {
            $this->service->status = 'RegistrationIsBlocked';
        }

        return $this->response($this->service->status,
            $this->service->response
                ? new UserFrontResource($this->service->response)
                : null
        );
    }


    public function resetPassword(UserFrontResetPasswordPost $request)
    {
        $this->service->resetPassword($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function sendResetLinkEmail(UserFrontForgetPasswordPost $request)
    {
        $this->service->sendResetLinkEmail($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserFrontStorePost $request)
    {
        $this->service->store($request->validated());

        return $this->response($this->service->status, $this->service->response);
    }


    public function editProfile(UserFrontEditPost $request)
    {
        $this->service->setUser($request->user('api'));
        $this->service->editProfile($request->validated());

        return $this->response($this->service->status, new UserFrontResource($this->service->response));
    }
}
