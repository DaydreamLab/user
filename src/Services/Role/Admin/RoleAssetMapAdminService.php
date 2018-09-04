<?php

namespace DaydreamLab\User\Services\Role\Admin;

use DaydreamLab\User\Repositories\Role\Admin\RoleAssetMapAdminRepository;
use DaydreamLab\User\Services\Role\RoleAssetMapService;
use Illuminate\Support\Str;

class RoleAssetMapAdminService extends RoleAssetMapService
{
    protected $type = 'RoleAssetMapAdmin';

    public function __construct(RoleAssetMapAdminRepository $repo)
    {
        parent::__construct($repo);
    }


    public function getGrant($id)
    {
        $items = $this->findBy('role_id', '=', $id);
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item->id;
        }
        $this->status = Str::upper(Str::snake($this->type.'GetGrantSuccess'));;
        $this->response = $ids;

        return $ids;
    }
}
