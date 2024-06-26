<?php

namespace DaydreamLab\User\Controllers\User\Front;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Requests\User\Front\UserFrontChangePasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontForgetPasswordPost;
use DaydreamLab\User\Requests\User\Front\UserFrontResetPasswordPost;
use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Requests\User\UserCheckEmailPost;
use DaydreamLab\User\Requests\User\UserFrontRegisterPost;
use DaydreamLab\User\Requests\User\UserLoginPost;
use DaydreamLab\User\Resources\User\Front\Models\UserFrontGetLoginResource;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use DaydreamLab\User\Requests\User\Front\UserFrontRemovePost;
use DaydreamLab\User\Requests\User\Front\UserFrontStorePost;
use DaydreamLab\User\Requests\User\Front\UserFrontStatePost;
use DaydreamLab\User\Requests\User\Front\UserFrontSearchPost;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Request;


class UserFrontController extends BaseController
{
    public function __construct(UserFrontService $service)
    {
        parent::__construct($service);
        $this->service = $service;
    }


    public function activate($token)
    {
        $this->service->activate($token);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function checkEmail(UserCheckEmailPost $request)
    {
        $this->service->status = 'USER_EMAIL_IS_NOT_REGISTERED';

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function changePassword(UserFrontChangePasswordPost $request)
    {
        $this->service->changePassword($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function fblogin()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }


    public function forgotPasswordTokenValidate($token)
    {
        $this->service->forgotPasswordTokenValidate($token);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }



    public function fbCallback()
    {
        $this->service->fblogin();

        return ResponseHelper::response($this->service->status, $this->service->response);
    }



    public function getLogin(Request $request)
    {
        $user = Auth::guard('api')->authenticate();
        if ($user)
        {
            $status = 'USER_GET_ITEM_SUCCESS';
            $response = $user;
            $response->token = $request->bearerToken();
        }
        else
        {
            $status = 'USER_TOKEN_EXPIRED';
            $response = null;
        }
        return ResponseHelper::response($status,  new UserFrontGetLoginResource($response));
    }


    public function login(UserLoginPost $request)
    {
        $this->service->login($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function register(UserFrontRegisterPost $request)
    {
        if (config('daydreamlab-user.register.enable'))
        {
            $this->service->register($request->rulesInput());
        }
        else
        {
            $this->service->status = 'USER_REGISTRATION_IS_BLOCKED';
        }

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function resetPassword(UserFrontResetPasswordPost $request)
    {
        $this->service->resetPassword($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function sendResetLinkEmail(UserFrontForgetPasswordPost $request)
    {
        $this->service->sendResetLinkEmail($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }



    /*
    public function getItem($id)
    {
        $this->service->getItem($id);

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function getItems()
    {
        $this->service->search(new Collection());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function remove(UserFrontRemovePost $request)
    {
        $this->service->remove($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function state(UserFrontStatePost $request)
    {
        $this->service->state($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function store(UserFrontStorePost $request)
    {
        $this->service->store($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }


    public function search(UserFrontSearchPost $request)
    {
        $this->service->search($request->rulesInput());

        return ResponseHelper::response($this->service->status, $this->service->response);
    }
    */
}
