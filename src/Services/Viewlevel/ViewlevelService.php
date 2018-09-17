<?php

namespace DaydreamLab\User\Services\Viewlevel;

use DaydreamLab\User\Repositories\Viewlevel\ViewlevelRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class ViewlevelService extends BaseService
{
    protected $type = 'Viewlevel';

    public function __construct(ViewlevelRepository $repo)
    {
        parent::__construct($repo);
    }
}
