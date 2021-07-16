<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\NotFoundException;
use DaydreamLab\JJAJ\Exceptions\UnauthorizedException;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Repositories\Company\Admin\CompanyAdminRepository;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;

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
        if (count($input->get('groupIds'))) {
            $item->groups()->attach($input->get('groupIds'));
        }


        if ($item->company) {
            $item->company->update($input->get('company'));
        } else {
            $company = $input->get('company');
            $company['user_id'] = $item->id;
            UserCompany::create($company);
        }
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


    public function getSelfPage()
    {

        $user   = $this->getUser();
        $groups = $user->groups;

        $pages = collect();
        $groups->each(function ($group) use (&$pages) {
            $pages = $pages->merge($group->page);
        });

        $this->status = 'GetSelfPageSuccess';
        $this->response = $pages;

        return $this->response;
    }


    public function modifyMapping($item, $input)
    {
        $item->groups()->sync($input->get('group_ids'), true);
        $item->company()->update($input->get('company'));
    }


    public function store(Collection $input)
    {
        if (InputHelper::null($input, 'id')) {
            $this->checkEmail($input->get('email'));
        }

        // 確保使用者所指派的群組，具有該權限
        $inputGroupIds = collect($input->get('group_ids'));
        $userAccessGroupIds = $this->getUser()->accessGroupIds;
        if ($inputGroupIds->intersect($userAccessGroupIds)->count() != $inputGroupIds->count()) {
            throw new UnauthorizedException('InsufficientPermissionAssignGroup', [
                'groupIds' => $inputGroupIds->diff($userAccessGroupIds)
            ]);
        }

        $result = parent::store($input);

        return $result;
    }


    public function removeMapping($item)
    {
        $item->groups()->detach();
    }
}
