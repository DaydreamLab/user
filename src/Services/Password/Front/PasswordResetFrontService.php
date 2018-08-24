<?php

namespace DaydreamLab\User\Services\Password\Front;

use DaydreamLab\User\Repositories\Password\Front\PasswordResetFrontRepository;
use DaydreamLab\User\Services\Password\PasswordResetService;

class PasswordResetFrontService extends PasswordResetService
{
    protected $type = 'PasswordResetFront';

    public function __construct(PasswordResetFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
