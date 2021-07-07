<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserGroupService extends BaseService
{
    protected $package = 'User';

    protected $modelName = 'UserGroup';

    protected $modelType = 'Base';

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


    // 建立使用者可以 access 的 user group 並且把不能 access 的標記 disabled = 1
    // 並且成樹狀列表
    public function tree()
    {
        $allGroups = $this->all()->where('title', '!=', 'Root');
        $canAccessGroupIds = $this->getUser()->accessGroups->pluck('id')->toArray();

        $tree = $allGroups->each(function ($item, $key) use ($canAccessGroupIds) {
            $item->disabled = in_array($item->id, $canAccessGroupIds)
                ? 0
                : 1;
        })->toTree();

        $this->status = 'GetTreeListSuccess';
        $this->response = $tree;

        return $tree;
    }
}
