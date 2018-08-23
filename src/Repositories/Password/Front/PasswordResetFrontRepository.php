<?php

namespace App\Repositories\Password\Front;

use App\Repositories\Password\PasswordResetRepository;
use App\Models\Password\Front\PasswordResetFront;

class PasswordResetFrontRepository extends PasswordResetRepository
{
    public function __construct(PasswordResetFront $model)
    {
        parent::__construct($model);
    }
}