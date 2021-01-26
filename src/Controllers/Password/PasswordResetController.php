<?php

namespace DaydreamLab\User\Controllers\Password;

use DaydreamLab\JJAJ\Controllers\BaseController;
use DaydreamLab\User\Services\Password\PasswordResetService;

class PasswordResetController extends BaseController
{
    public function __construct(PasswordResetService $service)
    {
        parent::__construct($service);
    }
}
