<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\Asset\Admin\AssetAdminRepository;
use DaydreamLab\User\Services\Asset\AssetService;

class AssetAdminService extends AssetService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    public function __construct(AssetAdminRepository $repo)
    {
        parent::__construct($repo);
    }


    public function treeList()
    {
        $tree = $this->repo->all()->toFlatTree();

        $tree = $tree->map(function ($item, $key) {
            return $item->only(['id', 'tree_list_title']);
        });

        $this->status = 'GetTreeListSuccess';
        $this->response = $tree;

        return $tree;
    }
}
