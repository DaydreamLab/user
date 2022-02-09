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
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
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


    public function export(Collection $input)
    {
        return $this->search($input);
    }


    public function addMapping($item, $input)
    {
        if (count($input->get('groupIds') ?: [])) {
            $item->groups()->attach($input->get('groupIds'));
        }

        if (count($input->get('brandIds') ?:[])) {
            $item->brands()->attach($input->get('brandIds'));
        }

        # 會員新增時，先檢查公司統編，若存在則更新會員使用者群組（一般會員、經銷會員），同時必定創建一個 userCompany
        $inputUserCompany = $input->get('company') ?: [];
        if (isset($inputUserCompany['name'])) {
            $company = $this->companyAdminRepo->findBy('name', '=', $inputUserCompany['name'])->first();
            if ($company) {
                $inputUserCompany['vat'] = $company->vat;
                $inputUserCompany['company_id'] = $company->id;
                $item->groups()->sync([$company->category->userGroupId]);
            }
        }
        $inputUserCompany['user_id'] = $item->id;
        $item->company()->create($inputUserCompany);
    }


    public function beforeRemove(Collection $input, $item)
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
            ? $action. 'Success'
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
    /**
     * 處理批次匯入訂單的會員資料建立or更新問題
     */
    public function createOrUpdate(Collection $input)
    {

    }


    public function getSelfPage($site_id)
    {
        $user   = $this->getUser();
        $groups = $user->groups;

        $pages = collect();
        $groups->each(function ($group) use (&$pages) {
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
        $changes = $item->groups()->sync($input->get('groupIds') ?: [], true);
        if (count($attached = $changes['attached'])) {
# 根據會員群組的變動更新電子報訂閱
            if (in_array(7, $attached)) {
                $categories = Item::whereIn('alias', ['01_newsletter'])->whereHas('category', function ($q) {
                    $q->where('content_type', 'newsletter_category');
                })->get()->pluck('id')->all();
            } elseif (in_array(6, $attached)) {
                $categories = Item::whereIn('alias', ['01_newsletter', '01_deal_newsletter'])->whereHas('category', function ($q) {
                    $q->where('content_type', 'newsletter_category');
                })->get()->pluck('id')->all();
            } else {
                $categories = [];
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
        }


        if ($item->company) {
            $inputUserCompany = $input->get('company') ?: [];
            if (isset($inputUserCompany['name'])) {
                $company = $this->companyAdminRepo->findBy('name', '=', $inputUserCompany['name'])->first();
                if ($company) {
                    $inputUserCompany['vat'] = $company->vat;
                    $inputUserCompany['company_id'] = $company->id;
                    $item->company->update($inputUserCompany);
                    $item->groups()->sync([$company->category->userGroupId]);
                } else {
                    # 統編資料不存在但是有填寫統編，則建立統編資料並歸戶同公司名稱的所有人員
                    if (isset($inputUserCompany['vat'])) {
                        $normalCategory = CompanyCategory::where('title', '一般會員')->first();
                        $company = $this->companyAdminRepo->create([
                            'name' => $inputUserCompany['name'],
                            'vat' => $inputUserCompany['vat'],
                            'phone' => $inputUserCompany['phone'],
                            'category_id' => $normalCategory->id
                        ]);

                        $q = new QueryCapsule();
                        $q->whereHas('company', function ($q) use ($inputUserCompany) {
                            $q->where('users_companies.name', $inputUserCompany['name']);
                        });
                        $companyUsers = $this->search(collect(['q' => $q]));
                        $companyUsers->each(function ($companyUser) use ($company) {
                            $companyUser->company()->update([
                                'company_id'    => $company->id,
                                'vat'           => $company->vat
                            ]);
                            $companyUser->groups()->sync([$company->category->userGroupId]);
                        });
                    } else {
                        $item->company->update($inputUserCompany);
                    }
                }
            }
        }

       // if (count($input->get('brandIds') ?: [])) {
            $item->brands()->sync($input->get('brandIds') ?: []);
       //}
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
            if (!$input->get('mobilePhone')) {
                $input->put('mobilePhone', Str::random(10));
            }
        }

        // 確保使用者所指派的群組，具有該權限
        $inputGroupIds = collect($input->get('groupIds'));
        $userAccessGroupIds = $this->getUser()->accessGroupIds;
        if ($inputGroupIds->intersect($userAccessGroupIds)->count() != $inputGroupIds->count()) {
            throw new UnauthorizedException('InsufficientPermissionAssignGroup', [
                'groupIds' => $inputGroupIds->diff($userAccessGroupIds)
            ]);
        }

        $result = parent::store($input);
        if ($input->has('id')) {
            $result = $this->find($input->get('id'));
        }
        if ( $result->block == 1 && in_array(config('app.env'), ['production', 'staging']) ) {
            $newsletterSSer = app(NewsletterSubscriptionAdminService::class);
            $newsletterSSer->edmAddBlackList($result->email);
        }
        $this->response = $result;

        return $result;
    }
}
