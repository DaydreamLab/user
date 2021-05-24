<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Exceptions\UnauthorizedException;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;

class UserAdminService extends UserService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    public function __construct(UserAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->response = $repo;
    }


    public function addMapping($item, $input)
    {
        if (count($input->get('groupIds'))) {
            $item->groups()->attach($input->get('group_ids'));
        }

        if ($input->has('company')) {
            $item->company()->create($input->get('company'));
        }
    }


    public function beforeRemove($item)
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

        $group_apis     = collect();
        $asset_assets   = \Kalnoy\Nestedset\Collection::make();
        foreach ($groups as $group)
        {
            $apis           = $group->apis()->get();
            $group_apis     = $group_apis->merge($apis);
            $assets         = $group->assets()->get();
            $asset_assets   = $asset_assets->merge($assets);
        }
        $asset_assets = $asset_assets->toTree();

        $apis = [];
        foreach ($group_apis as $group_api)
        {
            $temp_api_assets =  $group_api->assets()->get();
            foreach ($temp_api_assets as $temp_api_asset)
            {
                $temp_api_asset_id  = $temp_api_asset->id;
                if (!array_key_exists($temp_api_asset_id, $apis))
                {
                    $apis[$temp_api_asset_id] = [];
                }
                $apis[$temp_api_asset_id][] = $group_api->method;
            }
        }

        $response['apis']      = $apis;
        $response['assets']    = $asset_assets;

        $this->status = 'GetSelfPageSuccess';
        $this->response = $response;

        return $response;
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
