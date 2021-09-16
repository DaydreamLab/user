<?php

namespace DaydreamLab\User\Controllers\User\Front;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use phpDocumentor\Reflection\TypeResolver;
use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use DaydreamLab\User\Requests\User\Front\UserFrontLoginPost;
use DaydreamLab\User\Requests\User\Front\UserFrontStorePost;
use DaydreamLab\User\Requests\User\Front\UserFrontLineBindPost;
use DaydreamLab\User\Requests\User\Front\UserFrontRegisterPost;
use DaydreamLab\User\Requests\User\Front\UserFrontCheckEmailPost;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontResource;
use DaydreamLab\User\Requests\User\Front\UserFrontResetPasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontVerifyCodeRequest;
use DaydreamLab\User\Requests\User\Front\UserFrontChangePasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontForgetPasswordPost;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontLoginResource;
use DaydreamLab\User\Requests\User\Front\UserFrontCheckMobilePhoneRequest;
use DaydreamLab\User\Requests\User\Front\UserFrontRegisterMobilePhonePost;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontGetLoginResource;

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
        try {
            $this->service->activate($token);
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function checkEmail(UserFrontCheckEmailPost $request)
    {
        try {
            $this->service->checkEmail($request->validated()->get('email'));
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function checkMobilePhone(UserFrontCheckMobilePhoneRequest $request)
    {
        try {
            $this->service->checkMobilePhone($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function forgetChangePassword(UserFrontChangePasswordPost $request)
    {
        $this->service->setUser($request->user);
        try {
            $this->service->changePassword($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function fbLogin()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }


    public function fbCallback()
    {
        try {
            $this->service->fbLogin();
        } catch (Throwable $t) {
            $this->handleException($t);
        }
        return $this->response($this->service->status,
            gettype($this->service->response) == 'object'
            ? new UserFrontLoginResource($this->service->response)
            : $this->service->response
        );
    }


    public function forgotPasswordTokenValidate($token)
    {
        try {
            $this->service->forgotPasswordTokenValidate($token);
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function getItem(Request $request)
    {
        $this->service->status = 'GetItemSuccess';

        return $this->response($this->service->status,  $request->user('api'), [], UserFrontResource::class);
    }


    public function getByUUID($uuid)
    {
        try {
            $this->service->getByUUID($uuid);
        } catch (Throwable $t) {
            $this->handleException($t);
        }
        return $this->response($this->service->status,  $this->service->response, [], UserFrontResource::class);
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

        return $this->response($status,  $response , [], UserFrontGetLoginResource::class);
    }


    public function getVerificationCode(UserFrontCheckMobilePhoneRequest $request)
    {
        try {
            $this->service->getVerificationCode($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }


        return $this->response($this->service->status, $this->service->response);
    }


    public function login(UserFrontLoginPost $request)
    {
        if(config('daydreamlab.user.login.enable')) {
            try {
                $this->service->login($request->validated());
            } catch (Throwable $t) {
                $this->handleException($t);
            }
        } else {
            $this->service->status = 'LoginIsBlocked';
            $this->service->response = null;
        }

        return $this->response($this->service->status,  $this->service->response , [], UserFrontGetLoginResource::class);
    }


    public function lineBind(UserFrontLineBindPost $request)
    {
        try {
            $this->service->lineBind($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }

//    public function register(UserFrontRegisterPost $request)
//    {
//        if (config('daydreamlab.user.register.enable')) {
//            try {
//                $this->service->register($request->validated());
//            } catch (Throwable $t) {
//                $this->handleException($t);
//            }
//        } else {
//            $this->service->status = 'RegistrationIsBlocked';
//        }
//
//        return $this->response($this->service->status,
//            $this->service->response
//                ? new UserFrontResource($this->service->response)
//                : null
//        );
//    }

    public function register(UserFrontRegisterMobilePhonePost $request)
    {
        try {
            $this->service->registerMobilePhone($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function resetPassword(UserFrontResetPasswordPost $request)
    {
        try {
            $this->service->resetPassword($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function sendResetLinkEmail(UserFrontForgetPasswordPost $request)
    {
        try {
            $this->service->sendResetLinkEmail($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function store(UserFrontStorePost $request)
    {
        try {
            $this->service->modify($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function updateOldUser(UserFrontStorePost $request)
    {
        try {
            $this->service->updateOldUser($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function verifyVerificationCode(UserFrontVerifyCodeRequest $request)
    {
        try {
            $this->service->verifyVerificationCode($request->validated());
        } catch (Throwable $t) {
            $this->handleException($t);
        }

        return $this->response($this->service->status, $this->service->response);
    }


    public function lineRichmenu(Request $request)
    {
        $this->service->lineRichmenu($request);
    }


    public function lineBotChat(Request $request)
    {
        $this->service->lineBotChat($request);
    }


    public function linkAccount(Request $request, $lineId)
    {
        return $this->service->linkAccount($request, $lineId);
    }
}
