<?php

namespace DaydreamLab\User\Controllers\User;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\User\UserGroupService;

class UserGroupController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserGroup';

    protected $modelType = 'Base';

    public function __construct(UserGroupService $service)
    {
        parent::__construct($service);
    }
}
