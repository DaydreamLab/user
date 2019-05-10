<?php

namespace DaydreamLab\User\Services\Viewlevel\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Viewlevel\Admin\ViewlevelAdminRepository;
use DaydreamLab\User\Services\Viewlevel\ViewlevelService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ViewlevelAdminService extends ViewlevelService
{
    protected $type = 'ViewlevelAdmin';

    public function __construct(ViewlevelAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
