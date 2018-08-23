<?php

namespace App\Services\Password;

use App\Repositories\Password\PasswordResetRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class PasswordResetService extends BaseService
{
    protected $type = 'PasswordReset';

    public function __construct(PasswordResetRepository $repo)
    {
        parent::__construct($repo);
    }
}
