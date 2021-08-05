<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\Cms\Services\NewsletterSubscription\Front\NewsletterSubscriptionFrontService;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\InternalServerErrorException;
use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Notifications\ResetPasswordNotification;
use DaydreamLab\User\Notifications\User\UserGetVerificationCodeNotification;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\User\Services\Social\SocialUserService;
use Carbon\Carbon;
use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\User\UserService;
use DaydreamLab\User\Traits\CanSendNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;

class UserFrontService extends UserService
{
    use LoggedIn, CanSendNotification;

    protected $modelType = 'Front';

    protected $socialUserService;

    protected $passwordResetService;

    public function __construct(
        UserFrontRepository     $repo,
        SocialUserService       $socialUserService,
        PasswordResetService    $passwordResetService
    )
    {
        parent::__construct($repo);
        $this->socialUserService    = $socialUserService;
        $this->passwordResetService = $passwordResetService;
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
                throw new ForbiddenException('HasBeenActivated',  ['token' => $token]);
            } else {
                $this->repo->modify($user, collect(['activation' => 1]));
                $this->status = 'ActivationSuccess';
            }
        } else {
            throw new ForbiddenException('ActivationTokenInvalid',  ['token' => $token]);
        }
    }



    public function checkMobilePhone(Collection $input)
    {
        $mobilePhone = $input->get('mobilePhone');
        $user = $this->findBy('mobilePhone', '=', $mobilePhone)->first();
        if ($user) {
            throw new ForbiddenException('MobilePhoneExist', ['mobilePhone' => $mobilePhone]);
        }

        $this->status = 'MobilePhoneNotExist';
        $this->response = $user;

        return $this->response;
    }


    public function addMapping($item, $input)
    {
        $item->groups()->attach(config('daydreamlab.user.register.groups'));
    }


    public function fbLogin()
    {
        $fb_user = Socialite::driver('facebook')
            ->fields(['name', 'first_name', 'last_name', 'email', 'gender'])
            ->stateless()
            ->user();

        $q = new QueryCapsule();
        $q->where('provider', 'facebook')
            ->where('provider_id', $fb_user->id);
        $social_user = $this->socialUserService->search(collect(['q' => $q]))->first();
        if ($social_user) {     // 登入
            $user = $this->find($social_user->user_id);
            if ($user) {
                // 更新 token
                $social_user->token = $fb_user->token;
                $social_user->save();
                $this->status = 'FbLoginSuccess';
                $this->response = $this->helper->getUserLoginData($user) ;
            } else {
                $this->status = 'FbRegisterUnfinished';
                $this->response = null;
            }
        } else {
            return $this->fbRegister($fb_user);
        }

        return $social_user;
    }


    /**
     * @param $user User
     */
    public function fbRegister($fb_user)
    {
        if (!$fb_user->email) {
            $this->status = 'FbEmailRequired';
            return false;
        }

        $this->checkEmail($fb_user->email);

        $user_data  = $this->helper->mergeDataFbUserCreate($fb_user);
        $user       = $this->add($user_data);

        $social_data = $this->helper->mergeDataFbSocialUserCreate($fb_user, $user->id);
        $socialUser  = $this->socialUserService->store($social_data);

        $this->status = 'FbRegisterSuccess';
        $this->response = $this->helper->getUserLoginData($user) ;

        return $user;
    }


    public function forgotPasswordTokenValidate($token)
    {
        $reset_token = $this->passwordResetService->findBy('token', '=', $token)->last();
        if ($reset_token) {
            if (now() > Carbon::parse($reset_token->expired_at)) {
                throw new ForbiddenException('ResetPasswordTokenExpired', ['token' => $token]);
            } elseif ($reset_token->reset_at) {
                throw new ForbiddenException('ResetPasswordTokenIsUsed', ['token' => $token]);
            } else {
                $this->status = 'ResetPasswordTokenValid';
            }
        } else {
            throw new ForbiddenException('ResetPasswordTokenInvalid', ['token' => $token]);
        }

        return $reset_token;
    }


    public function getVerificationCode(Collection $input)
    {
        $user = $this->findBy('mobilePhone', '=', $input->get('mobilePhone'))->first();
        if (!$user) {
            $user = $this->store($input);
        }

        $code = config('app.env') == 'production' ? Helper::generateRandomIntegetString() : '0000';
        if (config('app.env') == 'production'
            && $user->lastSendAt
            && now()->diffInSeconds(Carbon::parse($user->lastSendAt)) < config('daydreamlab.user.sms.cooldown')
        ) {
            $diff = config('daydreamlab.user.sms.cooldown') - now()->tz('UTC')->diffInSeconds(Carbon::parse($user->lastSendAt, 'UTC'));
            throw new ForbiddenException('SendVerificationCodeInCoolDown', ['seconds' => $diff]);
        }

        # 寄送簡訊
        $this->sendNotification(
            'sms',
            $user->fullMobilePhone,
            new UserGetVerificationCodeNotification($code)
        );

        $this->repo->update($user, [
            'verificationCode' => bcrypt($code),
            'lastSendAt' => now()->toDateTimeString()
        ]);

        $this->status = 'SendVerificationCodeSuccess';
        $this->response = ['uuid' => $user->uuid];

        return $this->response;
    }

    /**
     * 編輯會員資訊
     * @param Collection $input
     * @return bool
     */
    public function modify(Collection $input)
    {
        $user = $this->getUser();
        $userData = $input->only(['uuid', 'name', 'email', 'backupEmail'])->all();
        $userData['verificationCode'] = bcrypt(Str::random());
        $update = $this->repo->update($user, $userData);
        if (!$update) {
            throw new InternalServerErrorException('UpdateFail');
        }

//        app(NewsletterSubscriptionFrontService::class)->
//
        $companyData = $input->get('company');
        $userCompany = $user->company;
        if ($userCompany) {
            $userCompany->update($companyData);
        } else {
            $companyData['user_id'] = $user->id;
            $user->company()->create($companyData);
        }

        if ( $subscribe = $input->get('newsletterCategoriesAlias') ) {
            $nsfs = app(NewsletterSubscriptionFrontService::class);
            $nsfs->store(collect(['newsletterCategoriesAlias' => $subscribe]));
        }


        $this->status = 'UpdateSuccess';
    }

    /**
     * 註冊帳號
     * @param Collection $input
     */
    public function register(Collection $input)
    {
        $this->checkEmail($input->get('email'));
        $user = $this->add($input);

        $user->notify(new RegisteredNotification($user));

        $this->status = 'RegisterSuccess';
        $this->response = $user->refresh();

        return $this->response;
    }


    public function registerMobilePhone(Collection $input)
    {
        $user = $this->findBy('uuid', '=', $input->get('uuid'))->first();
        if (!$user) {
            throw new NotFoundException('ItemNotExist');
        }

        $userData = $input->only(['uuid', 'name', 'email', 'backupEmail'])->all();
        $userData['verificationCode'] = bcrypt(Str::random());
        $update = $this->repo->update($user, $userData);
        if (!$update) {
            throw new InternalServerErrorException('RegisterFail');
        }

        $companyData = $input->get('company');
        $companyData['user_id'] = $user->id;
        $userCompany = UserCompany::create($companyData);
        if (!$userCompany) {
            throw new InternalServerErrorException('RegisterFail');
        }

        if ( $subscribe = $input->get('newsletterCategoriesAlias') ) {
            $nsfs = app(NewsletterSubscriptionFrontService::class);
            $nsfs->store(collect(['newsletterCategoriesAlias' => $subscribe]));
        }
        #todo 有沒有送通知?

        $this->status = 'RegisterSuccess';
    }


    public function resetPassword(Collection $input)
    {
        $token = $this->forgotPasswordTokenValidate($input->get('token'));
        $user  = $this->findBy('email', '=', $token->email)->first();

        if (Hash::check($input->get('password'), $user->password)) {
            throw new ForbiddenException('PasswordSameAsPrevious');
        }

        $this->passwordResetService->update($token, collect([
            'reset_at' => now()->toDateTimeString()
        ]));


        $this->repo->modify($user, collect([
            'password' => bcrypt($input->get('password')),
            'lastResetAt' => now()->toDateTimeString(),
            'lastPassword' => $user->password,
            'resetPassword' => 0
        ]));

        $user->tokens()->delete();
        $this->status = 'ResetPasswordSuccess';
    }


    public function sendResetLinkEmail(Collection $input)
    {
        $user = $this->findBy('email', '=', $input->get('email'))->first();
        if ($user) {

            $this->passwordResetService->findBy('email', '=', $input->get('email'))
                ->each(function ($p) {
                    $p->delete();
                });

            $token = $this->passwordResetService->add(collect([
                'email'         => $input->get('email'),
                'token'         => Str::random(128),
                'expired_at'    => now()->addHours(3)
            ]));

            $this->repo->modify($user, collect(['resetPassword' => 1]));
            $user->notify(new ResetPasswordNotification($user, $token));
            $this->status = 'ResetPasswordEmailSend';
        } else {
            throw new NotFoundException('ItemNotExist', $input);
        }
    }


    public function verifyVerificationCode(Collection $input)
    {
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
}
