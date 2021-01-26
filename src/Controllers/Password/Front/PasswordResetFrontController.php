<?php

namespace DaydreamLab\User\Controllers\Password\Front;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Password\Front\PasswordResetFrontService;

class PasswordResetFrontController extends BaseController
{
    public function __construct(PasswordResetFrontService $service)
    {
        parent::__construct($service);
    }
}
