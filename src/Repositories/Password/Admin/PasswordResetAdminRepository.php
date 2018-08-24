<?php

namespace DaydreamLab\User\Repositories\Password\Admin;

use DaydreamLab\User\Repositories\Password\PasswordResetRepository;
use DaydreamLab\User\Models\Password\Admin\PasswordResetAdmin;

class PasswordResetAdminRepository extends PasswordResetRepository
{
    public function __construct(PasswordResetAdmin $model)
    {
        parent::__construct($model);
    }
}