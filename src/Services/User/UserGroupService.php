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
    use NestedServiceTrait ;

    protected $type = 'UserGroup';

    public function __construct(UserGroupRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        return $this->storeNested($input);
    }


    public function tree()
    {
        $tree = $this->findBy('id', '!=', 1)->toTree();

        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeSuccess'));
        $this->response = $tree;

        return $tree;
    }


//    public function treeList()
//    {
//        $tree = $this->findBy('id', '!=', 1)->toFlatTree();
//
//        $tree = $tree->map(function ($item, $key) {
//            return $item->only(['id', 'tree_list_title']);
//        });
//
//        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeListSuccess'));
//        $this->response = $tree;
//
//        return $tree;
//    }

    public function treeList()
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

        $tree = $this->findBySpecial('whereIn', 'id', $with_ancestor_viewlevels)->toFlatTree();

        $viewlevels = $this->user->viewlevels;
        $tree = $tree->map(function ($item, $key) use ($viewlevels) {
            if (in_array($item->id, $viewlevels))
            {
                $item->disabled = 0;
            }
            else
            {
                $item->disabled = 1;
            }
            return $item->only(['id', 'tree_list_title', 'disabled']);
        });

        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeListSuccess'));
        $this->response = $tree;

        return $tree;
    }
}
