<?php

namespace App\Services\Password\Front;

use App\Repositories\Password\Front\PasswordResetFrontRepository;
use App\Services\Password\PasswordResetService;

class PasswordResetFrontService extends PasswordResetService
{
    protected $type = 'PasswordResetFront';

    public function __construct(PasswordResetFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
