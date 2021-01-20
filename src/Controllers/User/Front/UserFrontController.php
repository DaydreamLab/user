<?php

namespace DaydreamLab\User\Controllers\User\Front;

use Carbon\Carbon;
use DaydreamLab\User\Requests\User\Front\UserFrontChangePasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontForgetPasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontRegisterPost;
use DaydreamLab\User\Requests\User\Front\UserFrontResetPasswordPost;
use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\User\UserCheckEmailPost;
use DaydreamLab\User\Requests\User\UserLoginPost;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontGetLoginResource;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontLoginResource;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use DaydreamLab\User\Requests\User\Front\UserFrontStorePost;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Request;


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


    public function checkEmail(UserCheckEmailPost $request)
    {
        $this->service->status = 'USER_EMAIL_IS_NOT_REGISTERED';

        return $this->response($this->service->status, $this->service->response);
    }


    public function changePassword(UserFrontChangePasswordPost $request)
    {
        $this->service->changePassword($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function fblogin()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }


    public function forgotPasswordTokenValidate($token)
    {
        $this->service->forgotPasswordTokenValidate($token);

        return $this->response($this->service->status, $this->service->response);
    }



    public function fbCallback()
    {
        $this->service->fblogin();

        return $this->response($this->service->status, $this->service->response);
    }



    public function getLogin(Request $request)
    {
        $user = Auth::guard('api')->authenticate();
        if ($user) {
            $token = $user->token();
            if (Carbon::parse($token->expires_at)->diffInDays(now()) < 3)
            {
                $token->expires_at  = now()->addSeconds(config('daydreamlab.user.token_expires_in'));
                $token->save();
            }
            $status = 'USER_GET_ITEM_SUCCESS';
            $response = $user;
            $response->token = $request->bearerToken();
        } else {
            $status = 'USER_TOKEN_EXPIRED';
            $response = null;
        }
        return $this->response($status,  new UserFrontGetLoginResource($response));
    }


    public function login(UserLoginPost $request)
    {
        if(config('daydreamlab.user.login.enable')) {
            $this->service->login($request->rulesInput());
        } else {
            $this->service->status = 'LoginIsBlocked';
            $this->service->response = null;
        }

        return $this->response($this->service->status, $this->service->response ? new UserFrontLoginResource($this->service->response) : null);
    }


    public function register(UserFrontRegisterPost $request)
    {
        if (config('daydreamlab.user.register.enable'))
        {
            $this->service->register($request->rulesInput());
        } else {
            $this->service->status = 'USER_REGISTRATION_IS_BLOCKED';
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function resetPassword(UserFrontResetPasswordPost $request)
    {
        $this->service->resetPassword($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function sendResetLinkEmail(UserFrontForgetPasswordPost $request)
    {
        $this->service->sendResetLinkEmail($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return $this->response($this->service->status, $this->service->response);
    }
}
