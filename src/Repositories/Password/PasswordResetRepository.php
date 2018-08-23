<?php

namespace App\Repositories\Password;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use App\Models\Password\PasswordReset;

class PasswordResetRepository extends BaseRepository
{
    public function __construct(PasswordReset $model)
    {
        parent::__construct($model);
    }
}