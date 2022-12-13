<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\Cms\Models\Item\Item;
use DaydreamLab\Cms\Services\NewsletterSubscription\Admin\NewsletterSubscriptionAdminService;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\UnauthorizedException;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Helpers\OtpHelper;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserAdminService extends UserService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $companyAdminRepo;

    public function __construct(UserAdminRepository $repo, CompanyAdminRepository $companyAdminRepo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
        $this->companyAdminRepo = $companyAdminRepo;
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
        if (isset($inputUserCompany['vat'])) {
            $company = $this->companyAdminRepo->findBy('vat', '=', $inputUserCompany['vat'])->first();
            if ($company) {
                $inputUserCompany['name'] = $company->name;
                $inputUserCompany['vat'] = $company->vat;
                $inputUserCompany['company_id'] = $company->id;
                $item->groups()->sync([$company->category->userGroupId]);
            }
        }
        $inputUserCompany['user_id'] = $item->id;
        $item->company()->create($inputUserCompany);

        $data = [
            'user_id' => $item->id,
            'email' => $item->email
        ];
        $sub = app(NewsletterSubscriptionAdminService::class)->add(collect($data));
        if ($input->get('subscribeNewsletter')) {
            $this->decideNewsletterSubscription(['attached' => []], $item, $input);
        }

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


        if ($search = $input->get('search')) {
            $searchFilterUserIds = DB::table('users_companies')
                ->select('user_id')
                ->where('name', 'like', "%$search%")
                ->orWhere('vat', 'like', "%$search%")
                ->get()
                ->pluck('user_id')
                ->values()
                ?: collect();
            if ($searchFilterUserIds->count()) {
                $q->extraSearch(function ($q) use ($searchFilterUserIds) {
                    $q->whereIn('id', $searchFilterUserIds->all());
                });
            }
        }

        $q->select('id', 'name', 'email', 'mobilePhone', 'block', 'blockReason');
        $q->with('company');

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


    public function modifyMapping($item, $input)
    {
        $dealerUserGroup = UserGroup::where('title', '經銷會員')->first();
        $userGroup = UserGroup::where('title', '一般會員')->first();

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
                        'category_id'   => 5,
                    ]));
                }

                $updateData = [
                    'name'          => $company->name,
                    'vat'           => $company->vat,
                    'company_id'    => $company->id,
                    'email'         => $inputUserCompany['email']
                ];

                # 處理是否有更換公司 + 沒填部門、職稱就不變
                if ($userCompany->company_id != $company->id) {
                    $updateData['department'] = $inputUserCompany['department'];
                    $updateData['jobTitle'] = $inputUserCompany['jobTitle'];
                } else {
                    if ($inputUserCompany['department'] != '') {
                        $updateData['department'] = $inputUserCompany['department'];
                    }
                    if ($inputUserCompany['jobTitle'] != '') {
                        $updateData['jobTitle'] = $inputUserCompany['jobTitle'];
                    }
                }
                $inputCompany = $input->get('company');
                if ($userCompany->company && $userCompany->company->category->title == '經銷會員') {
                    # 原本沒過期強制變成已過期
                    if (isset($inputCompany['isExpired']) && $inputCompany['isExpired'] === '0')) {
                        $updateData['lastValidate'] = now()->toDateTimeString();
                    }
                } else {
                    $updateData['validated'] = 0;
                    $updateData['lastValidate'] = null;
                }

                $updateData['lastUpdate'] = now()->toDateTimeString();

                # 更新 userCompany
                $userCompany->update(array_merge($inputUserCompany, $updateData));

                # 取出管理者權限（如果有）
                $groupIds = $item->groups->pluck('id')->reject(function ($value) {
                     return in_array($value, [6,7]);
                })->values();

                if (in_array($company->category->title, ['經銷會員', '零壹員工'])) {
                    $groupIds->push(6);
                } else {
                    $groupIds->push(7);
                }

                $changes = $item->groups()->sync($groupIds->all());
                $this->decideNewsletterSubscription($changes, $item, $input);
            } else {
                $userCompany->update(array_merge($inputUserCompany, [
                    'name'          => @$inputUserCompany['name'],
                    'vat'           => @$inputUserCompany['vat'],
                    'department'    => @$inputUserCompany['department'],
                    'jobTitle'      => @$inputUserCompany['jobTitle'],
                    'company_id'    => null,
                    'validated'     => 0,
                    'lastValidate'  => null,
                    'lastUpdate'    => now()->toDateTimeString()
                ]));
                $changes = $item->groups()->sync([$userGroup->id]);
                $this->decideNewsletterSubscription($changes, $item, $input);
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
            $changes = $item->groups()->sync([$userGroup->id]);
            $this->decideNewsletterSubscription($changes, $item, $input);
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
            if ($input->get('subscribeNewsletter') === '0') {
                $categories = [];
            } elseif ($input->get('subscribeNewsletter') === '1' && !count($categories)) {
                $categories[] = $item->company->company
                    ? ($item->company->company->category->title == '一般會員' ? 35 : 36)
                    : 35;
            }

            $data = [
                'user_id' => $item->id,
                'email' => $item->email,
                'newsletterCategoryIds' => $categories
            ];

            $newsletterSSer = app(NewsletterSubscriptionAdminService::class);
            $sub = $newsletterSSer->findBy('user_id', '=', $item->id)->first();
            if ($sub) {
                $data['id'] = $sub->id;
                $newsletterSSer->modify(collect($data));
            } else {
                $sub = $newsletterSSer->add(collect($data));
            }
            $newsletterSSer->edmProcessSubscription($item->email, $sub); # 串接edm訂閱管理
        } else {
            $sub = $item->newsletterSubscription;
            $newsletterSSer = app(NewsletterSubscriptionAdminService::class);
            $subscribeNewsletter = $input->get('subscribeNewsletter');
            if ($subscribeNewsletter === '1' && !$sub->newsletterCategories->count()) {
                $categoryId = $item->company->company
                    ? ($item->company->company->category->title == '一般會員' ? 35 : 36)
                    : 35;
                $sub->newsletterCategories()->attach($categoryId);
                $sub->cancelReason = null;
                $sub->cancelAt = null;
                $sub->save();
                $newsletterSSer->edmAddSubscription($sub->email, ($categoryId == 35 ? 6 : 7));
            } elseif ($subscribeNewsletter === '0' && $sub->newsletterCategories->count()) {
                $categoryId = $item->company->company
                    ? ($item->company->company->category->title == '一般會員' ? 35 : 36)
                    : 35;
                $sub->cancelReason = $input->get('cancelReason');
                $sub->cancelAt = now()->toDateTimeString();
                $sub->save();
                $sub->newsletterCategories()->detach();
                $newsletterSSer->edmRemoveSubscription($sub->email, ($categoryId == 35 ? 6 : 7));
            }
        }
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
            $newsletterSSer = app(NewsletterSubscriptionAdminService::class);
            $newsletterSSer->edmAddBlackList($result->email);
        }
        $this->response = $result;

        return $result;
    }


    public function crmSearch(Collection $input)
    {
        return $this->search($input->only(['q', 'limit', 'paginate']));
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
