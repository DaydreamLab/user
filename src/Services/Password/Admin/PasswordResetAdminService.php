<?php

namespace App\Services\Password\Admin;

use App\Repositories\Password\Admin\PasswordResetAdminRepository;
use App\Services\Password\PasswordResetService;

class PasswordResetAdminService extends PasswordResetService
{
    protected $type = 'PasswordResetAdmin';

    public function __construct(PasswordResetAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
