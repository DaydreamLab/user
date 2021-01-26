<?php

namespace DaydreamLab\User\Controllers\User\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\User\Front\UserGroupFrontService;

class UserGroupFrontController extends BaseController
{
    protected $package = 'User';

    protected $modelName = 'UserGroup';

    protected $modelType = 'Front';

    public function __construct(UserGroupFrontService $service)
    {
        parent::__construct($service);
    }
}
