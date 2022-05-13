<?php

namespace DaydreamLab\User\Services\User\Front;

use DaydreamLab\Cms\Services\NewsletterSubscription\Front\NewsletterSubscriptionFrontService;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\InternalServerErrorException;
use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use DaydreamLab\JJAJ\Exceptions\UnauthorizedException;
use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Notifications\RegisteredNotification;
use DaydreamLab\User\Notifications\ResetPasswordNotification;
use DaydreamLab\User\Notifications\User\UserGetVerificationCodeNotification;
use DaydreamLab\User\Services\Password\PasswordResetService;
use DaydreamLab\User\Services\Social\SocialUserService;
use Carbon\Carbon;
use DaydreamLab\User\Repositories\Line\LineRepository;
use DaydreamLab\User\Repositories\User\Front\UserFrontRepository;
use DaydreamLab\User\Services\User\UserService;
use DaydreamLab\User\Traits\CanSendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use LINE\LINEBot;
use LINE\LINEBot\Event\AccountLinkEvent;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;

class UserFrontService extends UserService
{
    use LoggedIn, CanSendNotification;

    protected $modelType = 'Front';

    protected $socialUserService;

    protected $passwordResetService;

    protected $lineRepo;

    public function __construct(
        UserFrontRepository     $repo,
        SocialUserService       $socialUserService,
        PasswordResetService    $passwordResetService,
        LineRepository          $lineRepo
    )
    {
        parent::__construct($repo);
        $this->socialUserService    = $socialUserService;
        $this->passwordResetService = $passwordResetService;
        $this->lineRepo =  $lineRepo;
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


    public function getByUUID($uuid, $tokenUser)
    {
        $user = $this->findBy('uuid', '=', $uuid)->first();
        if (!$user) {
            throw new NotFoundException('ItemNotExist');
        }

        if ($tokenUser && $tokenUser->id != $user->id) {
            throw new UnauthorizedException('Unauthorized');
        }
//        if ($user->activation) {
//            throw new ForbiddenException('HasBeenActivated');
//        }
        $this->status = 'GetItemSuccess';
        $this->response = $user;
        return $this->response;
    }


    public function handleUserDataUpdate(Collection $input, $user)
    {
        $userData = $input->only(['uuid', 'name', 'email', 'backupEmail'])->all();
        if ($input->has('activation')) {
            $userData['activation'] = $input->get('activation');
        }
        if ($user->isAdmin()) {
            unset($userData['email']);
        }
        $update = $this->repo->update($user, $userData);
        if (!$update) {
            throw new InternalServerErrorException('UpdateFail');
        }

        $companyData = $input->get('company');
        # 如果有統編，取出公司資料，公司不存在則新增
        $cpy = $this->firstOrCreateCompany($companyData);
        if ($cpy) {
            $companyData['name'] = $cpy->name;
            $companyData['company_id'] = $cpy->id;
        }

        # 根據公司的身份決定使用者的群組
        $userGroupType = $this->decideUserGroup($user, $cpy, $companyData);

        # 更新電子報訂閱
        $this->handleUserNewsletterSubscription($input, $userGroupType, $user);

        # 新增或更新 userCompany
        $this->updateOrCreateUserCompany($user, $companyData);
    }



    public function handleUserNewsletterSubscription(Collection $input, $userGroupType, $user)
    {
        $nsfs = app(NewsletterSubscriptionFrontService::class);

        return $nsfs->store(collect([
            'subscribeNewsletter'       => $input->get('subscribeNewsletter'),
            'newsletterCategoriesAlias' => [$userGroupType == 'dealer' ? '01_deal_newsletter' : '01_newsletter'],
            'user'                      => $user->refresh(),
            'email'                     => $input->get('email')
        ]));
    }


    /**
     * 編輯會員資訊
     * @param Collection $input
     * @return bool
     */
    public function modify(Collection $input)
    {
        $user = $this->getUser();

        $this->handleUserDataUpdate($input, $user);

        $this->response = $user->refresh();
        $this->status = 'UpdateSuccess';
    }


    public function updateOldUser(Collection $input)
    {
        $user = $this->findBy('uuid', '=', $input->get('uuid'))->first();
        if (!$user) {
            throw new NotFoundException('ItemNotExist');
        }

        $input->put('activation', 1);
        $this->handleUserDataUpdate($input, $user);

        # 處理line綁定
        if ($input->get('lineId')) {
            $this->lineBind($input);
        }

        $tokens = $user->tokens()->get();
        if(!config('daydreamlab.user.multiple_login')) {
            $tokens->each(function ($token) {
                $token->multipleLogin = 1;
                $token->save();
            });
        }

        $tokenResult = $user->createToken(config('app.name'));
        $token = $tokenResult->token;
        $token->expires_at = now()->addSeconds(config('daydreamlab.user.token_expires_in'));
        $token->save();
        $user->accessToken = $tokenResult->accessToken;
        $this->response = $user;

        $this->status = 'UpdateSuccess';
        //$this->sendNotification('mail', $user->email, new RegisteredNotification($user));
    }


    public function updateOrCreateUserCompany($user, $companyData)
    {
        $userCompany = $user->company;
        if ($userCompany) {
            $userCompany->update($companyData);
        } else {
            $companyData['user_id'] = $user->id;
            $user->company()->create($companyData);
        }
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

        $userData = $input->only(['uuid', 'name', 'email', 'backupEmail'])->all();
        $userData['verificationCode'] = bcrypt(Str::random());
        $userData['activation'] = 1;
        $update = $this->repo->update($user, $userData);
        if (!$update) {
            throw new InternalServerErrorException('RegisterFail');
        }

        # 如果有統編，取出公司資料，公司不存在則新增
        $companyData = $input->get('company');
        $cpy = $this->firstOrCreateCompany($companyData);
        if ($cpy) {
            $companyData['name'] = $cpy->name;
            $companyData['company_id'] = $cpy->id;
        }

        # 根據公司的身份決定使用者的群組
        $userGroupType = $this->decideUserGroup($user, $cpy, $companyData);

        # 更新電子報訂閱
        $this->handleUserNewsletterSubscription($input, $userGroupType, $user);

        $companyData['user_id'] = $user->id;
        $userCompany = UserCompany::create($companyData);
        if (!$userCompany) {
            throw new InternalServerErrorException('RegisterFail');
        }

        # 檢查會蟲
        $this->checkBlacklist($user, $userCompany);

        # 通知
        $this->sendNotification('mail', $user->email, new RegisteredNotification($user));
        $this->status = 'RegisterSuccess';
    }


    public function firstOrCreateCompany($companyData)
    {
        if ($companyData['vat']) {
            $cpy = Company::where('vat', $companyData['vat'])->first();
            if (!$cpy) {
                $normalCategory = CompanyCategory::where('title', '一般')->first();
                $cpy = Company::create([
                    'name' => $companyData['name'],
                    'vat' => $companyData['vat'],
                    'category_id' => $normalCategory->id,
                    'mailDomains' => []
                ]);
            }
        } else {
            $cpy = null;
        }
        return $cpy;
    }


    public function decideUserGroup($user, $company, $input_company_data)
    {
        if (!$company) { // 沒有公司
            $user->groups()->sync(config('daydreamlab.user.register.groups'));
            return 'normal';
        }

        if ($company->category != null) { // 公司有分類
            if (in_array($company->category->title, ['經銷會員', '零壹員工'])) { // 經銷公司
                // 檢查 email 的 domain 跟公司 domain 是否相同
                $input_email = explode('@', $input_company_data['email']);
                if (isset($input_email[1]) && in_array($input_email[1], $company->mailDomains)) {
                    $isDealer = true; // domain 符合，使用者經銷會員
                } else {
                    $isDealer = false; // domain 不符合，使用者一般會員
                }
            } else {
                $isDealer = false; // 一般公司
            }
        } else {
            $isDealer = false; // 公司沒有分類，使用者歸類到一般會員
        }

        if ($isDealer) {
            $dealerUserGroup = UserGroup::where('title', '經銷會員')->first();
            if ($dealerUserGroup) {
                if ($user->isAdmin()) {
                    if (!in_array($dealerUserGroup->id, $user->groups->map(function ($g) { return $g->id; })->toArray())) {
                        $user->groups()->attach([$dealerUserGroup->id]);
                    }
                } else {
                    $user->groups()->sync([$dealerUserGroup->id]);
                }
            }
            return 'dealer';
        } else {
            $user->groups()->sync(config('daydreamlab.user.register.groups'));
            return 'normal';
        }
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


    public function lineBind(Collection $input)
    {
        $liffUserId = $input->get('lineId');
        $userId = auth()->guard("api")->user()->id;

        $isBinded = $this->lineRepo->getModel()::where("user_id", "=", $userId)->exists();
        if ($isBinded) {
            // 公司會員已綁定過 line
            $this->status = "LineBindDuplicate";
        } else {
            // 公司會員尚未綁定過 line
            $this->lineRepo->add(collect([
                "line_user_id" => $liffUserId,
                "user_id" => $userId,
            ]));
            $this->status = "LineBindSuccess";
        }
    }


    public function lineRichmenu(Request $request)
    {
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient(config('daydreamlab.user.linebot.accessToken'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('daydreamlab.user.linebot.channelSecret')
        ]);

        $res = $bot->createRichMenu(
            new RichMenuBuilder(
                RichMenuSizeBuilder::getHalf(),
                true,
                'ZeroneRichmenu',
                '功能選單',
                [
                    new RichMenuAreaBuilder(
                        new RichMenuAreaBoundsBuilder(0, 0, 1250, 843),
                        new LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("帳號綁定", "帳號綁定")
                    ),
                    new RichMenuAreaBuilder(
                        new RichMenuAreaBoundsBuilder(1250, 0, 1250, 843),
                        new LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("解除綁定", "解除綁定")
                    )
                ]
            )
        );

        if ($res->isSucceeded()) {
            $currentRichMenuID = $res->getJSONDecodedBody()['richMenuId'];
            $path = base_path().'/vendor/daydreamlab/user/resources/line/richmenu.jpg';
            if ( file_exists($path) ) {
                $res = $bot->uploadRichMenuImage($currentRichMenuID, $path, 'image/jpeg');
                if ($res->isSucceeded()) {
                    $res = $bot->linkRichMenu("all", $currentRichMenuID);
                    if ($res->isSucceeded()) {
                        echo 'succ';
                        return true;
                    }
                }
            }
        }
        echo 'fail';
        return false;
    }


    public function lineBotChat(Request $request)
    {
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient(config('daydreamlab.user.linebot.accessToken'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('daydreamlab.user.linebot.channelSecret')
        ]);

        $headers = $request->headers->all();
        $signature = $headers['x-line-signature'][0];
        if ($signature == '') {
            http_response_code(400);
            return;
        }

        $events = $bot->parseEventRequest($request->getContent(), $signature);

        foreach ($events as $event) {
            if ($event instanceof PostbackEvent) {
                $text = $event->getPostbackData();
                $lineId = $event->getUserId();
                switch ($text) {
                    case '帳號綁定':
                        $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\RawMessageBuilder([
                            'type' => 'flex',
                            'altText' => '帳號綁定',
                            'contents' => [
                                'type' => 'bubble',
                                'body' => [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        [
                                            'type' => 'text',
                                            'text' => '請點擊下方按鈕進行帳號綁定',
                                            'wrap' => true
                                        ]
                                    ]
                                ],
                                'footer' => [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        [
                                            'type' => 'button',
                                            'style' => 'primary',
                                            'action' => [
                                                'type' => 'uri',
                                                'label' => '開始綁定',
                                                'uri' => url('api/linebot/linkAccount/'.$lineId)
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]));
                        break;
                    case '解除綁定':
                        $user = $this->findBy('line_user_id', '=', $lineId)->first();
                        if ($user) {
                            $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\RawMessageBuilder([
                                'type' => 'flex',
                                'altText' => '解除綁定',
                                'contents' => [
                                    'type' => 'bubble',
                                    'body' => [
                                        'type' => 'box',
                                        'layout' => 'horizontal',
                                        'contents' => [
                                            [
                                                'type' => 'text',
                                                'text' => '確定解除帳號綁定？',
                                                'wrap' => true
                                            ]
                                        ]
                                    ],
                                    'footer' => [
                                        'type' => 'box',
                                        'layout' => 'horizontal',
                                        'contents' => [
                                            [
                                                'type' => 'button',
                                                'style' => 'primary',
                                                'action' => [
                                                    "type" => "postback",
                                                    "label" => "解除綁定",
                                                    "data" => "[TemplateMsg][AccountUnlink]"
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]));
                        } else {
                            $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\TextMessageBuilder('尚未綁定'));
                        }
                        break;
                    case strpos($text, '[TemplateMsg][AccountUnlink]') !== false:
                        $user = $this->findBy('line_user_id', '=', $lineId)->first();
                        $update = $this->repo->update($user, ['line_user_id' => null, 'line_nonce' => null]);
                        if ($update) {
                            $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\TextMessageBuilder('解除成功'));
                        } else {
                            $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\TextMessageBuilder('解除失敗'));
                        }
                        break;
                    default:
                        $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\TextMessageBuilder($text));
                        break;
                }
            } elseif ($event instanceof AccountLinkEvent) {
                $linkResult = $event->getResult();
                $lineUserId = $event->getUserId();
                $nonce = $event->getNonce();
                $user = $this->findBy('line_nonce', '=', $nonce)->first();
                $this->repo->update($user, ['line_user_id' => $lineUserId]);
                $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\TextMessageBuilder('綁定完成'));
            } else {
                $res = $bot->replyMessage($event->getReplyToken(), new LINEBot\MessageBuilder\TextMessageBuilder('您好'));
            }
        }
        http_response_code(200);
    }


    public function linkAccount(Request $request, $lineId)
    {
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient(config('daydreamlab.user.linebot.accessToken'));
        $bot = new LINEBot($httpClient, [
            'channelSecret' => config('daydreamlab.user.linebot.channelSecret')
        ]);

        $res = $bot->createLinkToken($lineId);
        if ($res->isSucceeded()) {
            $baseURL = url('login?lineLinkToken='. $res->getJSONDecodedBody()['linkToken']);

            return redirect($baseURL);
        }
    }

}
