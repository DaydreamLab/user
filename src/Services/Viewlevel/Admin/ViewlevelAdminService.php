<?php

namespace DaydreamLab\User\Services\Viewlevel\Admin;

use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;

class ViewlevelAdminService extends ViewlevelService
{
    protected $type = 'ViewlevelAdmin';

    public function __construct(ViewlevelAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
