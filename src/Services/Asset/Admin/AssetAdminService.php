<?php

namespace DaydreamLab\User\Services\Asset\Admin;


use DaydreamLab\User\Repositories\Asset\Admin\AssetAdminRepository;
use DaydreamLab\User\Services\Asset\AssetService;


class AssetAdminService extends AssetService
{
    protected $type = 'AssetAdmin';

    public function __construct(AssetAdminRepository $repo)
    {
        parent::__construct($repo);
    }

}
