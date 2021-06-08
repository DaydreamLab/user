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
}
