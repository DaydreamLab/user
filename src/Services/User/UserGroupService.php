<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserGroupService extends BaseService
{
    use NestedServiceTrait;

    protected $type = 'UserGroup';

    public function __construct(UserGroupRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function getTree()
    {
        $items = $this->repo->getTree();
        $this->status = Str::upper(Str::snake($this->type.'GetTreeSuccess'));;
        $this->response = $items;

        return $items;
    }


    public function store(Collection $input)
    {
        return $this->storeNested($input);
    }


    // 這只會建立使用者可以 access 的 user group 並且把不能 access 的標記 disabled = 1
    // 並且成樹狀列表
    public function tree()
    {
        $with_ancestor_viewlevels = [];
        foreach ($this->user->viewlevels as $viewlevel)
        {
            $with_ancestor_viewlevels[] = $viewlevel;
            $group = $this->find($viewlevel);
            foreach ($group->ancestors as $ancestor)
            {
                if ($ancestor->id != 1 && !in_array($ancestor->id, $with_ancestor_viewlevels))
                {
                    $with_ancestor_viewlevels[] = $ancestor->id;
                }
            }
        }

        $tree = $this->findBySpecial('whereIn', 'id', $with_ancestor_viewlevels);

        $viewlevels = $this->user->viewlevels;
        $tree = $tree->each(function ($item, $key) use ($viewlevels) {
            if (in_array($item->id, $viewlevels))
            {
                $item->disabled = 0;
            }
            else
            {
                $item->disabled = 1;
            }

        })->toTree();

        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeListSuccess'));
        $this->response = $tree;

        return $tree;
    }
}
