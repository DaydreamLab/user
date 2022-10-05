<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserGroupService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;

class UserGroupAdminService extends UserGroupService
{
    use LoggedIn;

    protected $viewlevelAdminService;

    protected $modelType = 'Admin';

    public function __construct(UserGroupAdminRepository $repo,
                                ViewlevelAdminService $viewlevelAdminService)
    {
        parent::__construct($repo);
        $this->viewlevelAdminService = $viewlevelAdminService;
        $this->repo = $repo;
    }


    public function addNested(Collection $input)
    {
        $item = parent::addNested($input);

        $ancestorsTitle =  $item->ancestors->pluck('title');
        if ($ancestorsTitle->intersect(['Administrator', 'Registered', 'Public', 'Guest'])->count()) {
            $adminViewlevel = $this->viewlevelAdminService->findBy('title', '=', 'Administrator')->first();
            $adminViewlevel->groups()->attach($item->id);
            $adminGroup = $this->repo->findBy('title','=', 'Administrator')->first();
            $adminGroup->defaultAccessGroups()->attach($item->id);
        }

        $superUserViewlevel = $this->viewlevelAdminService->findBy('title', '=', 'Super User')->first();
        $superUserGroup = $this->repo->findBy('title', '=', 'Super User')->first();
        $superUserViewlevel->groups()->attach($item->id);
        $superUserGroup->defaultAccessGroups()->attach($item->id);

        $newViewLevel = $this->viewlevelAdminService->store(collect([
            'title' => $item->title,
            'canDelete' => 0,
            'groupIds' => [$item->id]
        ]));
        $item->access = $newViewLevel->id;
        $item->save();

        return $item->refresh();
    }


    public function addMapping($item, $input)
    {
        $page = $input->get('page') ? : [];
        foreach ($page as $assetGroup) {
            $assets = $assetGroup['assets'];
            $assetGroupId = $assetGroup['id'];
            if ($assetGroup['visible']) {
                $item->assetGroups()->attach($assetGroupId);
            }
            foreach ($assets as $asset) {
                $assetId = $asset['id'];
                $apis = $asset['apis'];
                if ($asset['visible']) {
                    $item->assets()->attach($assetId);
                }
                foreach ($apis as $api) {
                    if ($api['checked']) {
                        $item->apis()->attach($api['id'], ['asset_group_id' => $assetGroupId, 'asset_id' => $assetId]);
                    }
                }
            }
        }
    }


    public function beforeRemove(Collection &$input, &$item)
    {
        if (!$item->canDelete) {
            $pk = $this->repo->getModel()->getPrimaryKey();
            throw new ForbiddenException('IsPreserved', [$pk => $item->{$pk}]);
        }
    }


    public function modifyMapping($item, $input)
    {
        $page = $input->get('page') ? : [];
        foreach ($page as $assetGroup) {
            $assets = $assetGroup['assets'];
            $assetGroupId = $assetGroup['id'];
            if ($assetGroup['visible']) {
                $item->assetGroups()->syncWithoutDetaching($assetGroupId);
            } else {
                $item->assetGroups()->detach($assetGroupId);
            }
            foreach ($assets as $asset) {
                $assetId = $asset['id'];
                $apis = $asset['apis'];
                if ($asset['visible']) {
                    $item->assets()->syncWithoutDetaching($assetId);
                } else {
                    $item->assets()->detach($assetId);
                }
                foreach ($apis as $api) {
                    if ($api['checked']) {
                        $pivot = $item->apis()->wherePivot('api_id', $api['id'])->wherePivot('asset_group_id', $assetGroupId)->wherePivot('asset_id', $assetId)->first();
                        if (!$pivot) {
                            $item->apis()->attach($api['id'], ['asset_group_id' => $assetGroupId, 'asset_id' => $assetId]);
                        }
                    } else {
                        $pivot = $item->apis()->wherePivot('api_id', $api['id'])->wherePivot('asset_group_id', $assetGroupId)->wherePivot('asset_id', $assetId)->first();
                        if ($pivot) {
                            $item->apis()->wherePivot('api_id', $api['id'])->wherePivot('asset_group_id', $assetGroupId)->wherePivot('asset_id', $assetId)->detach();
                        }
                    }
                }
            }
        }
    }


    public function removeMapping($item)
    {
        $item->assetGroups()->detach();
        $item->assets()->detach();
        $item->apis()->detach();
        $item->descendants()->each(function ($descendant) {
           $descendant->viewlevels()->detach();
        });
        $item->viewlevels()->detach();
    }
}
