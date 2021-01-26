<?php

namespace DaydreamLab\User\Services\Viewlevel\Admin;

use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;

class ViewlevelAdminService extends ViewlevelService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    public function __construct(ViewlevelAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
