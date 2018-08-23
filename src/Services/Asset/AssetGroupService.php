<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\User\Repositories\Asset\AssetGroupRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class AssetGroupService extends BaseService
{
    protected $type = 'AssetGroup';

    public function __construct(AssetGroupRepository $repo)
    {
        parent::__construct($repo);
    }
}
