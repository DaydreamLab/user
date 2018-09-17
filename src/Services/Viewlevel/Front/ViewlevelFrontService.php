<?php

namespace DaydreamLab\User\Services\Viewlevel\Front;

use DaydreamLab\User\Repositories\Viewlevel\Front\ViewlevelFrontRepository;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;

class ViewlevelFrontService extends ViewlevelService
{
    protected $type = 'ViewlevelFront';

    public function __construct(ViewlevelFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
