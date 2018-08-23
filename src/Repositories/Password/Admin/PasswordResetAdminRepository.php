<?php

namespace App\Repositories\Password\Admin;

use App\Repositories\Password\PasswordResetRepository;
use App\Models\Password\Admin\PasswordResetAdmin;

class PasswordResetAdminRepository extends PasswordResetRepository
{
    public function __construct(PasswordResetAdmin $model)
    {
        parent::__construct($model);
    }
}