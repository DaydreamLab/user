<?php

namespace DaydreamLab\User\Repositories\Password\Front;

use DaydreamLab\User\Repositories\Password\PasswordResetRepository;
use DaydreamLab\User\Models\Password\Front\PasswordResetFront;

class PasswordResetFrontRepository extends PasswordResetRepository
{
    public function __construct(PasswordResetFront $model)
    {
        parent::__construct($model);
    }
}