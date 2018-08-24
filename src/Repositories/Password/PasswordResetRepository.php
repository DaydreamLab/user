<?php

namespace DaydreamLab\User\Repositories\Password;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Password\PasswordReset;

class PasswordResetRepository extends BaseRepository
{
    public function __construct(PasswordReset $model)
    {
        parent::__construct($model);
    }
}