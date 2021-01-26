<?php

namespace DaydreamLab\User\Controllers\Password\Admin;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Password\Admin\PasswordResetAdminService;

class PasswordResetAdminController extends BaseController
{
    public function __construct(PasswordResetAdminService $service)
    {
        parent::__construct($service);
    }
}
