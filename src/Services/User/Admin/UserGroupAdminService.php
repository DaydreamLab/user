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

        $parent = $item->parent;
        if ($parent && $parent->ancestors->pluck('title')->contains('Administrator')) {
            $adminViewlevel = $this->viewlevelAdminService->findBy('title', '=', 'Administrator')->first();
            $adminViewlevel->groups()->attach($item->id);
        }

        $superUserViewlevel = $this->viewlevelAdminService->findBy('title', '=', 'Super User')->first();
        $superUserViewlevel->groups()->attach($item->id);

        return $item;
    }


    public function addMapping($item, $input)
    {
        $item->assets()->attach($input->get('asset_ids'));
        $item->apis()->attach($input->get('api_ids'));
    }


    public function beforeRemove(Collection $input, $item)
    {
        if (!$item->canDelete) {
            $pk = $this->repo->getModel()->getPrimaryKey();
            throw new ForbiddenException('IsPreserved', [$pk => $item->{$pk}]);
        }
    }


    public function getItem($input)
    {
        $group = parent::getItem(collect(['id' => $input->get('id')]));

        $group->assetGroups = $group->assetGroups->map(function ($item){
            return $item->id;
        });

        $group->apis = $group->apis->map(function ($item){
            return $item->id;
        });

        return $group;
    }


    public function modifyMapping($item, $input)
    {
        $item->assets()->sync($input->get('asset_ids'), true);
        $item->apis()->sync($input->get('api_ids'), true);
    }


    public function removeMapping($item)
    {
        $item->assets()->detach();
        $item->apis()->detach();
        $item->descendants()->each(function ($descendant) {
           $descendant->viewlevels()->detach();
        });
        $item->viewlevels()->detach();
    }
}
