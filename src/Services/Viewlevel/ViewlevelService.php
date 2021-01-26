<?php

namespace DaydreamLab\User\Services\Viewlevel;

use DaydreamLab\User\Repositories\Viewlevel\ViewlevelRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class ViewlevelService extends BaseService
{
    protected $package = 'User';

    protected $modelName = 'Viewlevel';

    protected $modelType = 'Base';

    public function __construct(ViewlevelRepository $repo)
    {
        parent::__construct($repo);
    }
}
