<?php

namespace DaydreamLab\User\Services\Password\Admin;

use DaydreamLab\User\Repositories\Password\Admin\PasswordResetAdminRepository;
use DaydreamLab\User\Services\Password\PasswordResetService;

class PasswordResetAdminService extends PasswordResetService
{
    protected $type = 'PasswordResetAdmin';

    public function __construct(PasswordResetAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
