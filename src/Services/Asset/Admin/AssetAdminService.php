<?php

namespace DaydreamLab\User\Services\Asset\Admin;


use DaydreamLab\User\Repositories\Asset\Admin\AssetAdminRepository;
use DaydreamLab\User\Services\Asset\AssetService;
use Illuminate\Support\Str;


class AssetAdminService extends AssetService
{
    protected $type = 'AssetAdmin';

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

        $this->status =  Str::upper(Str::snake($this->type . 'GetTreeListSuccess'));
        $this->response = $tree;

        return $tree;
    }

}
