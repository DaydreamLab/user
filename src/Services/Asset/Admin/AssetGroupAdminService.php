<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\User\Repositories\Asset\Admin\AssetGroupAdminRepository;
use DaydreamLab\User\Services\Asset\AssetGroupService;

class AssetGroupAdminService extends AssetGroupService
{
    protected $type = 'AssetGroupAdmin';

    public function __construct(AssetGroupAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
