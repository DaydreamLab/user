<?php

namespace DaydreamLab\User\Services\User\Admin;

use Carbon\Carbon;
use DaydreamLab\Cms\Helpers\EnumHelper as CmsEnumHelper;
use DaydreamLab\Cms\Models\Item\Item;
use DaydreamLab\Cms\Services\NewsletterSubscription\Admin\NewsletterSubscriptionAdminService;
use DaydreamLab\Dsth\Helpers\EnumHelper as DsthEnumHelper;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\InternalServerErrorException;
use DaydreamLab\JJAJ\Exceptions\UnauthorizedException;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\RequestHelper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Helpers\CompanyHelper;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Helpers\OtpHelper;
use DaydreamLab\User\Jobs\ImportNonePhoneUser;
use DaydreamLab\User\Jobs\ImportUpdateUser;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\Company\CompanyCategoryRepository;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Repositories\UserTag\Admin\UserTagAdminRepository;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class UserAdminService extends UserService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $companyAdminRepo;

    protected $newsletterSubscriptionAdminService;


    protected $userTagAdminRepository;

    protected $userGroupAdminRepo;

    protected $companyCategoryRepo;


    public function __construct(
        UserAdminRepository $repo,
        CompanyAdminRepository $companyAdminRepo,
        NewsletterSubscriptionAdminService $newsletterSubscriptionAdminService,
        UserTagAdminRepository $userTagAdminRepository,
        UserGroupAdminRepository $userGroupAdminRepo,
        CompanyCategoryRepository $companyCategoryRepo
    ) {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->companyAdminRepo = $companyAdminRepo;
        $this->newsletterSubscriptionAdminService = $newsletterSubscriptionAdminService;
        $this->userTagAdminRepository = $userTagAdminRepository;
        $this->userGroupAdminRepo = $userGroupAdminRepo;
        $this->companyCategoryRepo = $companyCategoryRepo;
    }


    public function addMapping($item, $input)
    {
        if (count($input->get('groupIds') ?: [])) {
            $item->groups()->attach($input->get('groupIds'));
        }

        if (count($input->get('brandIds') ?: [])) {
            $item->brands()->attach($input->get('brandIds'));
        }

        # 會員新增時，先檢查公司統編，若存在則更新會員使用者群組（一般會員、經銷會員），同時必定創建一個 userCompany
        $inputUserCompany = $input->get('company') ?: [];
        if (isset($inputUserCompany['vat']) && $inputUserCompany['vat'] !== '') {
            $company = $this->companyAdminRepo->findBy('vat', '=', $inputUserCompany['vat'])->first();
            if ($company) {
                $inputUserCompany['name'] = $company->name;
                $inputUserCompany['vat'] = $company->vat;
                $inputUserCompany['company_id'] = $company->id;
                CompanyHelper::updatePhonesByUserPhones($company, $inputUserCompany);
                $outerGroup = $this->userGroupAdminRepo->findBy('title', '=', '外部會員')->first();
                $nonePhoneGroup = $this->userGroupAdminRepo->findBy('title', '=', '無手機名單')->first();
                if (
                    $input->get('groupIds') !== [$outerGroup->id]
                    && $input->get('groupIds') !== [$nonePhoneGroup->id]
                ) {
                    $item->groups()->sync([$company->category->userGroupId]);
                }
            }
        } else {
            # 判斷domain 是不是原廠
            $company = CompanyHelper::checkOemByUserEmail($inputUserCompany);
            if ($company) {
                $inputUserCompany['name'] = $company->name;
                $inputUserCompany['company_id'] = $company->id;
            }
        }

        $inputUserCompany['user_id'] = $item->id;
        $item->company()->create($inputUserCompany);

        $this->decideNewsletterSubscription(['attached' => []], $item, $input);

        # 檢查會蟲
        $this->checkBlacklist($item, $item->refresh()->company);
    }


    public function beforeRemove(Collection &$input, $item)
    {
        if (!$item->canDelete) {
            throw new ForbiddenException('IsPreserved');
        }
    }


    public function block(Collection $input)
    {
        $result = false;
        foreach ($input->get('ids') as $key => $id) {
            $user           = $this->find($id);
            $result         = $this->repo->update($user, ['block' => $input->get('block')]);
            if (!$result) {
                break;
            }
        }

        $block = $input->get('block');
        if ($block == '1') {
            $action = 'Block';
        } elseif ($block == '0') {
            $action = 'Unblock';
        }

        event(new Block($this->getServiceName(), $result, $input, $this->user));

        $this->status = $result
            ? $action . 'Success'
            : $action . 'Fail';

        return $result;
    }


    public function checkMobilePhone($mobilePhone)
    {
        $user = $this->findBy('mobilePhone', '=', $mobilePhone)->first();
        if ($user) {
            throw new ForbiddenException('MobilePhoneExist', ['mobilePhone' => $mobilePhone]);
        }
        return $user;
    }


    public function export(Collection $input)
    {
        $q = $input->get('q');

        $parent_group = $input->get('parent_group');
        $child_group = $input->get('user_group');
        if ($parent_group || $child_group) {
            $q->whereIn('id', function ($q) use ($parent_group, $child_group) {
                $q->select('user_id')->from('users_groups_maps');
                if ($parent_group) {
                    $g = UserGroup::where('id', $parent_group)->first();
                    $c = $g->descendants->pluck(['id'])->toArray();
                    $ids = array_merge($c, [$g->id]);
                    $q->whereIn('users_groups_maps.group_id', $ids);
                }
                if ($child_group) {
                    is_array($child_group)
                        ? $q->whereIn('users_groups_maps.group_id', $child_group)
                        : $q->where('users_groups_maps.group_id', $child_group);
                }
            });
        }

        $q->with('line');
        $input->put('q', $q);
        $input->forget(['parent_group', 'user_group']);

        $users = $this->search($input);

        return $users;
    }


    public function getSelfPage($site_id)
    {
        $user   = $this->getUser();
        $groups = $user->groups;

        $dealerUserGroup = UserGroup::where('title', '經銷會員')->first();
        $userGroup = UserGroup::where('title', '一般會員')->first();
        $pages = collect();
        $groups->filter(function ($g) use ($dealerUserGroup, $userGroup) {
            return $g->id != $dealerUserGroup->id && $g->id != $userGroup->id;
        })->each(function ($group) use (&$pages) {
            $pages = $pages->merge($group->page);
        });

        $pages = $pages->filter(function ($p) use ($site_id) {
            return $p['site_id'] == $site_id;
        })->values();

        $this->status = 'GetSelfPageSuccess';
        $this->response = $pages;

        return $this->response;
    }


    public function importNonePhone(Collection $input)
    {
        $file = $input->get('file');
        $filename = Str::random(5) . '-importNonePhone-' . now('Asia/Taipei')->format('YmdHis');
        $file->storeAs('/uploads', $filename);

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(storage_path('app/uploads/' . $filename));
        $sheet = $spreadsheet->getSheet(0);
        $rows = $sheet->getHighestRow() + 1;
        $perFileRows = 1000;
        $jobCount = $rows / $perFileRows + 1;
        for ($i = 0; $i < $jobCount; $i++) {
            dispatch(new ImportNonePhoneUser(storage_path('app/uploads/' . $filename), $i, $perFileRows));
        }

        $this->status = 'ImportProcessing';
    }

    public function importUpdate(Collection $input)
    {
        $file = $input->get('file');
        $filename = Str::random(5) . '-importUpdateUser-' . now('Asia/Taipei')->format('YmdHis');
        $file->storeAs('/uploads', $filename);

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(storage_path('app/uploads/' . $filename));
        $sheet = $spreadsheet->getSheet(1);
        $rows = $sheet->getHighestRow();
        $jobCount = $rows / 1000 + 1;
        for ($i = 0; $i < $jobCount; $i++) {
            dispatch(new ImportUpdateUser(storage_path('app/uploads/' . $filename), $i));
        }

        $this->status = 'ImportProcessing';
    }


    public function modifyMapping($item, $input)
    {
        $dealerUserGroup =  $this->userGroupAdminRepo->findBy('title', '=', '經銷會員')->first();
        $normalUserGroup = $this->userGroupAdminRepo->findBy('title', '=', '一般會員')->first();
        $outerUserGroup = $this->userGroupAdminRepo->findBy('title', '=', '外部會員')->first();
        $nonePhoneUserGroup = $this->userGroupAdminRepo->findBy('title', '=', '無手機名單')->first();
        $isOuterUser = $item->groups->pluck('id')->contains($outerUserGroup->id);
        $isNonePhoneUser = $item->groups->pluck('id')->contains($nonePhoneUserGroup->id);

        if ($input->get('editAdmin')) {
            $item->brands()->sync($input->get('brandIds') ?: []);
            $admin_group_ids = $input->get('groupIds');
            if (!collect($admin_group_ids)->intersect($item->groups->pluck('id')->all())->count()) {
                $item->tokens()->each(function ($t) {
                    $t->revoke();
                });
            }
            if (in_array($dealerUserGroup->id, $item->groups->pluck('id')->all())) {
                $admin_group_ids[] = $dealerUserGroup->id;
            }
            $item->groups()->sync($admin_group_ids);
            # 編輯權限帳號只做到這邊就 return
            return;
        }

        $userCompany = $item->refresh()->company;
        $inputUserCompany = $input->get('company') ?: [];
        if ($userCompany) {
            if (isset($inputUserCompany['vat']) && $inputUserCompany['vat']) {
                $company = $this->companyAdminRepo->findBy('vat', '=', $inputUserCompany['vat'])->first();
                if (!$company) {
                    $company = $this->companyAdminRepo->add(collect([
                        'vat'   => $inputUserCompany['vat'],
                        'name'  => $inputUserCompany['name'],
                        'category_id'   => $this->companyAdminRepo
                            ->findBy('title', '=', '一般')
                            ->first()
                            ->id,
                    ]));
                }

                CompanyHelper::updatePhonesByUserPhones($company, $inputUserCompany);

                $updateData = [
                    'name'          => $company->name,
                    'vat'           => $company->vat,
                    'company_id'    => $company->id,
                    'email'         => $inputUserCompany['email']
                ];

                $updateData['department'] = $inputUserCompany['department'];
                if ($inputUserCompany['department'] != '') {
                    $updateData['department'] = $inputUserCompany['department'];
                }
                if ($inputUserCompany['jobTitle'] != '') {
                    $updateData['jobTitle'] = $inputUserCompany['jobTitle'];
                }

                if ($userCompany->company && $userCompany->company->category->title == '經銷會員') {
                    $inputValidateStatus = $input->get('validateStatus');
                    if ($item->validateStatus != $inputValidateStatus) {
                        if ($inputValidateStatus ==  EnumHelper::DEALER_VALIDATE_WAIT) {
                            $updateData['validated'] = 0;
                        } elseif ($inputValidateStatus ==  EnumHelper::DEALER_VALIDATE_EXPIRED) {
                            $updateData['validated'] = 1;
                            $updateData['lastValidate'] = now()
                                ->subDays(config('daydreamlab.user.userCompanyUpdateInterval') + 1)
                                ->toDateTimeString();
                        } else {
                            $updateData['validated'] = 1;
                            $updateData['lastValidate'] = now()->toDateTimeString();
                        }
                    }
                } else {
                    $updateData['validated'] = 0;
                    $updateData['lastValidate'] = null;
                }

                $updateData['lastUpdate'] = now()->toDateTimeString();

                # 更新 userCompany
                $r = $userCompany->update(array_merge($inputUserCompany, $updateData));

                # 取出管理者權限（如果有）
                $groupIds = $item->groups->pluck('id')->reject(
                    function ($value) use ($dealerUserGroup, $normalUserGroup, $outerUserGroup) {
                        return in_array($value, [$dealerUserGroup->id, $normalUserGroup->id, $outerUserGroup->id]);
                    }
                )->values();

                if (in_array($company->category->title, ['經銷會員', '零壹員工'])) {
                    $groupIds->push($dealerUserGroup->id);
                } elseif ($isOuterUser) {
                    $groupIds->push($outerUserGroup->id);
                } elseif ($isNonePhoneUser) {
                    $groupIds->push($nonePhoneUserGroup->id);
                } else {
                    $groupIds->push($normalUserGroup->id);
                }

                $changes = $item->groups()->sync($groupIds->all());
            } else {
                $oem = CompanyHelper::checkOemByUserEmail($inputUserCompany);
                $userCompany->update(array_merge($inputUserCompany, [
                    'name'          => $oem ? $oem->name :  @$inputUserCompany['name'],
                    'vat'           => @$inputUserCompany['vat'],
                    'department'    => @$inputUserCompany['department'],
                    'jobTitle'      => @$inputUserCompany['jobTitle'],
                    'company_id'    => $oem ? $oem->id : null,
                    'validated'     => 0,
                    'lastValidate'  => null,
                    'lastUpdate'    => now()->toDateTimeString()
                ]));
                $changes = $item->groups()->sync([
                    $isOuterUser
                        ? $outerUserGroup->id
                        : ($isNonePhoneUser ? $nonePhoneUserGroup->id : $normalUserGroup->id)
                ]);
            }
        } else {
            UserCompany::create(array_merge($inputUserCompany, [
                'user_id'       => $item->id,
                'name'          => @$inputUserCompany['name'],
                'vat'           => @$inputUserCompany['vat'],
                'department'    => @$inputUserCompany['department'],
                'jobTitle'      => @$inputUserCompany['jobTitle'],
                'validated'     => 0,
                'lastValidate'  => null,
                'lastUpdate'    => now()->toDateTimeString()
            ]));
            $changes = $item->groups()->sync([    $isOuterUser
                ? $outerUserGroup->id
                : ($isNonePhoneUser ? $nonePhoneUserGroup->id : $normalUserGroup->id)
            ]);
        }

        if (!$input->get('importUpdateUser')) {
            $this->decideNewsletterSubscription($changes, $item->refresh(), $input);
        }
    }


    protected function decideNewsletterSubscription($changes, $item, $input = null)
    {
        if (count($attached = $changes['attached'])) {
            # 根據會員群組的變動更新電子報訂閱
            if (in_array(7, $attached)) {
                if ($item->newsletterSubscription && $item->newsletterSubscription->newsletterCategories->count()) {
                    $categories = Item::whereIn('alias', ['01_newsletter'])->whereHas('category', function ($q) {
                        $q->where('content_type', 'newsletter_category');
                    })->get()->pluck('id')->all();
                } else {
                    $categories = [];
                }
            } elseif (in_array(6, $attached)) {
                if ($item->newsletterSubscription && $item->newsletterSubscription->newsletterCategories->count()) {
                    $categories = Item::whereIn('alias', ['01_deal_newsletter'])->whereHas('category', function ($q) {
                        $q->where('content_type', 'newsletter_category');
                    })->get()->pluck('id')->all();
                } else {
                    $categories = [];
                }
            } else {
                $categories = [];
            }

            # 處理訂閱設定的部份
            if ($input->get('subscribeNewsletter') === '0' || $input->get('subscribeNewsletter') === 0) {
                $categories = [];
            } elseif (
                ($input->get('subscribeNewsletter') === '1' || $input->get('subscribeNewsletter') == 1)
                && !count($categories)
            ) {
                $categories[] = $item->company->company
                    ? ($item->company->company->category->title == '一般會員' ? 35 : 36)
                    : 35;
            }

            $data = [
                'user_id' => $item->id,
                'email' => $item->email,
                'newsletterCategoryIds' => $categories
            ];

            $sub = $this->newsletterSubscriptionAdminService->findBy('user_id', '=', $item->id)->first();
            if ($sub) {
                $data['id'] = $sub->id;
                $this->newsletterSubscriptionAdminService->modify(collect($data));
            } else {
                $this->newsletterSubscriptionAdminService->add(collect($data));
            }
        } else {
            $sub = $item->newsletterSubscription;
            $data = [
                'user_id' => $item->id,
                'email' => $item->company->email
            ];
            if (!$sub) {
                $sub = $this->newsletterSubscriptionAdminService->add(collect($data));
            }
            $data['id'] = $sub->id;
            $subscribeNewsletter = $input->get('subscribeNewsletter');
            if ($subscribeNewsletter === '1' || $subscribeNewsletter === 1) {
                # 無訂閱 > 有訂閱
                $categoryId = $item->company->company
                    ? ($item->company->company->category->title == '一般' ? 35 : 36)
                    : 35;
                $data['cancelReason'] = null;
                $data['cancelAt'] = null;
                $data['newsletterCategoryIds'] = [$categoryId];
                $this->newsletterSubscriptionAdminService->modify(collect($data));
            } elseif ($subscribeNewsletter === '0' || $subscribeNewsletter === 0) {
                # 有訂閱 > 無訂閱
                $data['cancelReason'] = $input->get('cancelReason');
                $data['cancelAt'] = now()->toDateTimeString();
                $data['newsletterCategoryIds'] = [];
                $this->newsletterSubscriptionAdminService->modify(collect($data));
            }
        }
    }


    public function handleBasicQuery(Collection &$validated)
    {
        $basic = $validated->get('basic');
        $menu = $validated->get('menu');
        $except = $validated->get('except');
        $q = $validated->get('q');
        # 排除 noneCustomer、白日夢超級管理者、白日夢管理者、零壹管理者
//        $q->whereNotIn('id', [1,2,4,48464]);

        if ($basic['userGroup'] || $except['userGroup']) {
            $q->whereHas('groups', function ($q) use ($basic, $except) {
                if ($basic['userGroup']) {
                    $q->where('users_groups.title', $basic['userGroup']);
                }
                if ($except['userGroup']) {
                    $q->where('users_groups.title', '!=', $except['userGroup']);
                }
            });
        }

        if ($basic['lineBind'] == '是') {
            $q->whereHas('line');
        } elseif ($basic['lineBind'] == '否') {
            $q->whereDoesntHave('line');
        }

        if ($basic['createdAtFrom'] || $basic['createdAtTo']) {
            $q->whereNotNull('created_at');
            if ($basic['createdAtFrom']) {
                $q->where('created_at', '>=', RequestHelper::toSystemTime($basic['createdAtFrom']));
            }
            if ($basic['createdAtTo']) {
                $q->where(
                    'created_at',
                    '<=',
                    RequestHelper::toSystemTime($basic['createdAtTo'], 'Asia/Taipei', 'endOfDay')
                );
            }
        }

        if ($basic['lastLoginAtFrom'] || $basic['lastLoginAtTo'] || $except['lastLoginDate']) {
            $q->whereNotNull('lastLoginAt');
            if ($basic['lastLoginAtFrom']) {
                $q->where('lastLoginAt', '>=', RequestHelper::toSystemTime($basic['lastLoginAtFrom']));
            }
            if ($basic['lastLoginAtTo']) {
                $q->where(
                    'lastLoginAt',
                    '<=',
                    RequestHelper::toSystemTime($basic['lastLoginAtTo'], 'Asia/Taipei', 'endOfDay')
                );
            }
            # 介面好像又拿掉了
            if ($except['lastLoginDate']) {
                $q->where(
                    'lastLoginAt',
                    '<',
                    now()->tz($this->getUser()->timezone)
                        ->subDays($except['lastLoginDate'])
                        ->startOfDay()
                        ->tz(config('app.timezone'))
                        ->toDateTimeString()
                );
            }
        }

        if (isset($basic['canMarketing']) && $basic['canMarketing'] && config('app.marketing_message_limit_enabled')) {
            $q->select('*', DB::raw('(SELECT COUNT(*) FROM notification_records where users.id = notification_records.userId) as marketing_messages_count'));
            if ($basic['canMarketing'] == '可行銷') {
                $q->having('marketing_messages_count', '<', config('app.marketing_month_messages'));
            } else {
                $q->having('marketing_messages_count', '>=', config('app.marketing_month_messages'));
            }
        }

        if ($block = $validated->get('block')) {
            $q->where('block', $block);
        }

        if (
            $basic['lastUpdate']
            || $basic['lastUpdateFrom']
            || $basic['lastUpdateTo']
            || count($basic['purchaseRoles'] ?: [])
            || count($basic['jobTypes'] ?: [])
            || count($basic['jobCategories'] ?: [])
            || count($basic['interestedIssues'] ?: [])
        ) {
            $q->whereHas('company', function ($q) use ($basic) {
                if ($basic['lastUpdate'] && $basic['lastUpdate'] == '已更新') {
                    $q->whereNotNull('lastUpdate');
                } elseif ($basic['lastUpdate'] && $basic['lastUpdate'] == '未更新') {
                    $q->whereNull('lastUpdate');
                }
                if ($basic['lastUpdateFrom'] || $basic['lastUpdateTo']) {
                    $q->whereNotNull('users_companies.lastUpdate');
                    if ($basic['lastUpdateFrom']) {
                        $q->where(
                            'users_companies.lastUpdate',
                            '>=',
                            RequestHelper::toSystemTime($basic['lastUpdateFrom'])
                        );
                    }
                    if ($basic['lastUpdateTo']) {
                        $q->where(
                            'users_companies.lastUpdate',
                            '<=',
                            RequestHelper::toSystemTime($basic['lastUpdateTo'], 'Asia/Taipei', 'endOfDay')
                        );
                    }
                }
                if (count($basic['purchaseRoles'] ?: [])) {
                    $q->whereIn('purchaseRole', $basic['purchaseRoles']);
                }
                if (count($basic['jobTypes'] ?: [])) {
                    $q->whereIn('jobType', $basic['jobTypes']);
                }
                if (count($basic['jobCategories'] ?: [])) {
                    $q->whereIn('jobCategory', $basic['jobCategories']);
                }
                if (count($basic['interestedIssues'] ?: [])) {
                    $q->where(function ($q) use ($basic) {
                        foreach ($basic['interestedIssues'] ?: [] as $issue) {
                            $q->orWhereJsonContains('interestedIssue', $issue);
                        }
                    });
                }
            });
        }

        if ($basic['subscription'] == CmsEnumHelper::NEWSLETTER_SUBSCRIBE) {
            $q->whereHas('newsletterSubscription.newsletterCategories');
        } elseif ($basic['subscription']  == CmsEnumHelper::NEWSLETTER_NONESUBSCRIBE) {
            $q->whereDoesntHave('newsletterSubscription.newsletterCategories');
        } elseif ($basic['subscription']  == CmsEnumHelper::NEWSLETTER_UNSUBSCRIBE) {
            $q->whereHas('newsletterSubscription', function ($q) {
                $q->whereNotNull('cancelAt');
            });
        }

        if ($menu['id']) {
            if ($menu['action']) {
                $closure = function ($q) use ($menu) {
                    $q->where('menu_logs.menuId', $menu['id']);
                    if ($menu['startDate']) {
                        $q->where(
                            'menu_logs.created_at',
                            '>=',
                            RequestHelper::toSystemTime($menu['startDate'], 'Asia/Taipei', 'startOfDay')
                        );
                    }
                    if ($menu['endDate']) {
                        $q->where(
                            'menu_logs.created_at',
                            '<=',
                            RequestHelper::toSystemTime($menu['endDate'], 'Asia/Taipei', 'endOfDay')
                        );
                    }
                };
                if ($menu['action'] == '點擊') {
                    $q->withCount(['menuLogs' => $closure]);
                } else {
                    $q->withSum(['menuLogs' => $closure], 'time');
                }
            } else {
                throw new InternalServerErrorException(
                    'InputInvalid',
                    ['key' => 'menu[\'action\']'],
                    null,
                    'User'
                );
            }
            if ($menu['action'] == '點擊') {
                $q->having('menu_logs_count', '>=', $menu['value']);
            } elseif ($menu['action'] == '停留時間') {
                $q->having('menu_logs_sum_time', '>=', $menu['value']);
            }
        }

        $validated->put('q', $q);
    }

    public function handleCompanyQuery(&$validated)
    {
        $company = $validated->get('company');
        $companyOrder = $validated->get('companyOrder');
        $except = $validated->get('except');
        $q = $validated->get('q');

        # 有排除公司條件：無公司或有公司但是統編、公司名稱不含...
        $exceptCompanySearch = $except['companySearch'] ?: [];
        if (count($exceptCompanySearch)) {
            $q->whereIn('users.id', function ($q) use ($exceptCompanySearch) {
                $q->select('uc.user_id')
                    ->from('users_companies as uc')
                    ->leftJoin('companies as c', 'c.id', '=', 'uc.company_id')
                    ->where(function ($q) use ($exceptCompanySearch) {
                        $q->whereNull('uc.company_id')
                            ->orWhere(function ($q) use ($exceptCompanySearch) {
                                foreach ($exceptCompanySearch as $input) {
                                    if ($input !== null) {
                                        $q->where('c.name', 'not like', "%$input%")
                                            ->where('c.vat', 'not like', "%$input%");
                                    }
                                }
                            })
                        ;
                    });
            });
        }

        if (
            count($company['city'] ?: [])
            || count($company['categoryNotes'] ?: [])
            || count($company['industry'] ?: [])
            || count($company['search'] ?: [])
            || $company['scale']
            || count($except['companyCategoryNotes'] ?: [])
            || $companyOrder['enable'] == '是'
            || $company['approvedFrom']
            || $company['approvedTo']
            || $company['expiredFrom']
            || $company['expiredTo']
        ) {
            $q->whereHas('company.company', function ($q) use ($company, $companyOrder, $except) {
                $countNotes = count($company['categoryNotes'] ?: []);
                $countExceptNotes = count($except['companyCategoryNotes'] ?: []);
                if ($countNotes || $countExceptNotes) {
                    if ($countNotes) {
                        $q->whereIn('companies.categoryNote', $company['categoryNotes']);
                    }
                    if ($countExceptNotes) {
                        $q->whereNotIn('companies.categoryNote', $company['categoryNotes']);
                    }
                }

                $companySearch = $company['search'] ?: [];
                if (!empty($companySearch)) {
                    $q->where(function ($q) use ($companySearch) {
                        foreach ($companySearch as $search) {
                            if ($search !== null) {
                                $q->orWhere('companies.vat', 'like', "%{$search}%")
                                    ->orWhere('companies.name', 'like', "%{$search}%");
                            }
                        }
                    });
                }

                $industry = $company['industry'] ?: [];
                if (count($industry)) {
                    $q->where(function ($q) use ($industry) {
                        foreach ($industry as $i) {
                            $q->orWhere('companies.industry', 'like', "%{$i}%");
                        }
                    });
                }

                $city = $company['city'] ?: [];
                if (count($city)) {
                    $q->where(function ($q) use ($city) {
                        foreach ($city as $c) {
                            $q->orWhereJsonContains('phones', ['phoneCode' => $c]);
                        }
                    });
                }
                if ($company['scale']) {
                    $q->where('companies.scale', $company['scale']);
                }

                if ($companyOrder['enable'] == '是') {
                    $q->whereHas('orders', function ($q) use ($companyOrder) {
                        $q->whereIn('company_orders.brandId', $companyOrder['brands']);
                        if ($companyOrder['type'] == '符合全部勾選') {
                            $q->select(DB::raw('COUNT(DISTINCT(brandId)) as brandCount'))
                                ->having('brandCount', count($companyOrder['brands']));
                        }
                        if ($companyOrder['startDate']) {
                            $q->where(
                                'company_orders.date',
                                '>=',
                                RequestHelper::toSystemTime($companyOrder['startDate'], 'Asia/Taipei', 'startOfMonth')
                            );
                        }
                        if ($companyOrder['endDate']) {
                            $q->where(
                                'company_orders.date',
                                '<=',
                                RequestHelper::toSystemTime($companyOrder['endDate'], 'Asia/Taipei', 'endOfMonth')
                            );
                        }
                    });
                }

                if ($company['approvedFrom']) {
                    $q->where('approvedAt', '>=', RequestHelper::toSystemTime($company['approvedFrom']));
                }
                if ($company['approvedTo']) {
                    $q->where('approvedAt', '<=', RequestHelper::toSystemTime($company['approvedTo']));
                }
                if ($company['expiredFrom']) {
                    $q->where('expiredAt', '>=', RequestHelper::toSystemTime($company['expiredFrom']));
                }
                if ($company['expiredTo']) {
                    $q->where('expiredAt', '<=', RequestHelper::toSystemTime($company['expiredTo']));
                }
            });
        }

        $validated->put('q', $q);
    }


    public function handleOrderEventCouponQuery(&$validated)
    {
        $order = $validated->get('order');
        $event = $validated->get('event');
        $coupon = $validated->get('coupon');
        $except = $validated->get('except');

        $eventValues = [
            $event['search'],
            $event['category'],
            $event['type'],
            $event['canRegisterGroup'],
            count($event['brands'] ?: []),
            $event['dateType'],
            $event['registrationType'],
            $event['startDate'],
            $event['endDate'],
            $event['isOuter'],
            $event['waiting'],
        ];

        $orderValues = [
            $order['regStatus'],
            $order['participateTimesFrom'],
            $order['participateTimesTo'],
            $order['cancelTimesFrom'],
            $order['cancelTimesTo'],
            $order['noshowTimesFrom'],
            $order['noshowTimesTo'],
        ];

        $couponValues = [
            $coupon['type'],
            $coupon['userGroup']
        ];

        $exceptValues = [
            $except['cancelTimesFrom'],
            $except['cancelTimesTo'],
            $except['noshowTimesFrom'],
            $except['noshowTimesTo'],
        ];

        $q = $validated->get('q');

        if ($order['replyQuestionnaire'] == '是') {
            $q->whereHas('questionnaire');
        } elseif ($order['replyQuestionnaire'] == '否') {
            $q->whereDoesntHave('questionnaire');
        }

        if ($this->valueOr($couponValues)) {
            $q->whereHas('couponGroups', function ($q) use ($coupon) {
                if (in_array($coupon['type'], ['一般上課券', '批次上課券']) || $coupon['userGroup']) {
                    if ($coupon['type'] == '一般上課券') {
                        $q->where('type', 'normal');
                    }
                    if ($coupon['type'] == '批次上課券') {
                        $q->where('type', 'normal');
                    }
                    if ($coupon['userGroup']) {
                        $q->whereHas('userGroups', function ($q) use ($coupon) {
                            if (in_array($coupon['userGroup'], ['一般會員', '經銷會員'])) {
                                $q->where('users_groups.title', $coupon['userGroup']);
                            }
                        });
                    }
                }
            });
        }

        if ($this->valueOr($orderValues) || $this->valueOr($eventValues) || $this->valueOr($exceptValues)) {
            $q->whereHas('orders', function ($q) use ($order, $event, $except, $orderValues, $eventValues) {

//                if ($order['waiting'] == '是') {
//                    $q->whereHas('itemsWaiting');
//                } elseif ($order['waiting'] == '否') {
//                    $q->whereNotIn('orders.id', function ($q) {
//                        $q->select('orderId')
//                            ->from('order_items_waiting');
//                    });
//                }

                if ($this->valueOr($orderValues)) {
                    $participateTimesFrom = $order['participateTimesFrom'];
                    $participateTimesTo = $order['participateTimesTo'];
                    if ($participateTimesFrom || $participateTimesTo) {
                        $q->select(['userId', DB::raw('COUNT(*) as ordersCount')])
                            ->groupBy('userId');
                        if ($participateTimesFrom) {
                            $q->having('ordersCount', '>=', $participateTimesFrom);
                        }
                        if ($participateTimesTo) {
                            $q->having('ordersCount', '<=', $participateTimesTo);
                        }
                    }
                    if (
                        $order['cancelTimesFrom']
                        || $order['cancelTimesTo']
                        || $order['noshowTimesFrom']
                        || $order['noshowTimesTo']
                        || $except['cancelTimesFrom']
                        || $except['cancelTimesTo']
                        || $except['noshowTimesFrom']
                        || $except['noshowTimesTo']
                    ) {
                        $q->whereHas('items', function ($q) use ($order, $except, $event) {
                            $cancelTimesFrom = $order['cancelTimesFrom'];
                            $cancelTimesTo = $order['cancelTimesTo'];
                            $exceptCancelTimesFrom = $except['cancelTimesFrom'];
                            $exceptCancelTimesTo = $except['cancelTimesTo'];
                            if ($cancelTimesFrom || $cancelTimesTo || $exceptCancelTimesFrom || $exceptCancelTimesTo) {
                                $q->select(['orderId', DB::raw('COUNT(*) as cancelOrdersCount')])
                                    ->where('order_items.regStatus', DsthEnumHelper::CANCELED)
                                    ->groupBy('orderId');
                                if ($cancelTimesFrom) {
                                    $q->having('cancelOrdersCount', '>=', $cancelTimesFrom);
                                }
                                if ($cancelTimesTo) {
                                    $q->having('cancelOrdersCount', '<=', $cancelTimesTo);
                                }
                                if ($exceptCancelTimesFrom) {
                                    $q->having('cancelOrdersCount', '<', $exceptCancelTimesFrom);
                                }
                                if ($exceptCancelTimesTo) {
                                    $q->having('cancelOrdersCount', '>', $exceptCancelTimesTo);
                                }
                            }

                            $noshowTimesFrom = $order['noshowTimesFrom'];
                            $noshowTimesTo = $order['noshowTimesTo'];
                            $exceptNoshowTimesFrom = $except['noshowTimesFrom'];
                            $exceptNoshowTimesTo = $except['noshowTimesTo'];
                            if ($noshowTimesFrom || $noshowTimesTo || $exceptNoshowTimesFrom || $exceptNoshowTimesTo) {
                                $q->select(['orderId', DB::raw('COUNT(*) as noshowOrdersCount')])
                                    ->where('order_items.regStatus', DsthEnumHelper::NOSHOW)
                                    ->groupBy('orderId');
                                if ($noshowTimesFrom) {
                                    $q->having('noshowOrdersCount', '>=', $noshowTimesFrom);
                                }
                                if ($noshowTimesTo) {
                                    $q->having('noshowOrdersCount', '<=', $noshowTimesTo);
                                }
                                if ($exceptNoshowTimesFrom) {
                                    $q->having('noshowOrdersCount', '<', $exceptCancelTimesFrom);
                                }
                                if ($exceptNoshowTimesTo) {
                                    $q->having('noshowOrdersCount', '>', $exceptNoshowTimesTo);
                                }
                            }
                        });
                    }
                }

                if ($this->valueOr($eventValues)) {
                    $q->whereHas('event', function ($q) use ($event) {
                        if ($event['waiting']) {
                            $q->where('events.canWait', $event['waiting'] == '是' ? 1 : 0);
                        }

                        if ($event['isOuter']) {
                            $q->where('events.isOuter', $event['isOuter'] == '內部活動' ? 0 : 1);
                        }

                        if ($event['search']) {
                            $q->where(function ($q) use ($event) {
                                $q->orWhere('events.title', 'like', "%{$event['search']}%")
                                    ->orWhere('events.description', 'like', "%{$event['search']}%");
                            });
                        }

                        if ($event['category']) {
                            $q->whereHas('category', function ($q) use ($event) {
                                $q->where('event_categories.title', $event['category']);
                            });
                        }

                        if ($event['type'] == '實體') {
                            $q->where('events.type', 'physical');
                        } elseif ($event['type'] == '線上') {
                            $q->where('events.type', 'online');
                        }

                        if ($event['canRegisterGroup'] == '一般會員') {
                            $q->where('events.canRegisterGroup', 7);
                        } elseif ($event['canRegisterGroup'] == '經銷會員') {
                            $q->where('events.canRegisterGroup', 6);
                        }

                        $brands = $event['brands'] ?: [];
                        if (count($brands)) {
                            $q->whereHas('brands', function ($q) use ($brands) {
                                $q->whereIn('brands.title', $brands);
                            });
                        }

                        if ($event['dateType'] == '單天') {
                            $q->where('events.dateType', 'single');
                        } elseif ($event['dateType'] == '多天') {
                            $q->where('events.dateType', 'multiple');
                        } elseif ($event['dateType'] == '系列') {
                            $q->where('events.dateType', 'series');
                        }

                        if ($event['registrationType'] == '統一報名') {
                            $q->where('events.registrationType', 'impartial');
                        } elseif ($event['registrationType'] == '依場次報名') {
                            $q->where('events.registrationType', 'partial');
                        }

                        $startDate = $event['startDate'];
                        $endDate = $event['endDate'];
                        if ($startDate || $endDate) {
                            $q->whereHas('dates', function ($q) use ($startDate, $endDate) {
                                if ($startDate) {
                                    $q->where(
                                        'event_dates.date',
                                        '>=',
                                        RequestHelper::toSystemTime($startDate, 'Asia/Taipei', 'startOfDay')
                                    );
                                }
                                if ($endDate) {
                                    $q->where(
                                        'event_dates.date',
                                        '<=',
                                        RequestHelper::toSystemTime($endDate, 'Asia/Taipei', 'endOfDay')
                                    );
                                }
                            });
                        }
                    });
                }
            });
        }

        $validated->put('q', $q);
    }

    public function valueOr(array $array)
    {
        $v = 0;
        foreach ($array as $value) {
            $v = $v || $value;
        }

        return $v;
    }


    public function removeMapping($item)
    {
        $item->groups()->detach();
        $item->brands()->detach();
    }


    public function store(Collection $input)
    {
        if (InputHelper::null($input, 'id')) {
//            $this->checkEmail($input->get('email'));
            //$this->checkMobilePhone($input->get('mobilePhone'));
//            if (!$input->get('mobilePhone')) {
//                $input->put('mobilePhone', Str::random(10));
//            }
        }

        // 確保使用者所指派的群組，具有該權限
        $inputGroupIds = collect($input->get('groupIds'));
        $userAccessGroupIds = $this->getUser()->accessGroupIds;
        if ($inputGroupIds->intersect($userAccessGroupIds)->count() != $inputGroupIds->count()) {
            if (!$input->get('id')) {
                throw new UnauthorizedException('InsufficientPermissionAssignGroup', [
                    'groupIds' => $inputGroupIds->diff($userAccessGroupIds)
                ]);
            } else {
                $user = $this->find($input->get('id'));
                if ($inputGroupIds->intersect($user->groups->pluck('id')->all())->count() != $inputGroupIds->count()) {
                    throw new UnauthorizedException('InsufficientPermissionAssignGroup', [
                        'groupIds' => $inputGroupIds
                    ]);
                }
            }
        }

        $result = parent::store($input);

        if ($input->get('id')) {
            $result = $this->find($input->get('id'));
        }

        if ($result->block == 1 && in_array(config('app.env'), ['production', 'staging'])) {
            $this->newsletterSubscriptionAdminService->edmAddBlackList($result->email);
        }
        $this->response = $result;

        return $result;
    }


    public function crmSearch(Collection $input)
    {
        $this->handleBasicQuery($input);
        $this->handleCompanyQuery($input);
        $this->handleOrderEventCouponQuery($input);

        $inputTags = $input->get('userTags') ?: [];
        if (count($inputTags)) {
            $userIds = collect();
            foreach ($inputTags as $inputTag) {
                $userTag = $this->userTagAdminRepository
                    ->find($inputTag['id'], (new QueryCapsule())->with('activeUsers'));
                $userIds = $userTag
                    ? $userIds->merge($userTag->activeUsers->pluck('id'))->unique()->values()
                    : collect();
            }
            $q = $input->get('q');
            if ($userIds->count()) {
                $q->whereIn('id', $userIds);
            }
            $input->put('q', $q);
        }

        return $this->search($input->only(['search', 'company_id', 'updateStatus', 'q', 'limit', 'paginate']));
    }


    public function sendTotp($input)
    {
        $ids = $input->get('ids') ?? [];

        if (empty($ids)) {
            return;
        } else {
            $users = User::whereIn('id', $ids)->get()->each(function ($user) {
                OtpHelper::createTotp($user);
            });
        }

        $this->status = 'SendTotpSecretSuccess';
        $this->response = null;
    }
}
