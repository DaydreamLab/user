<?php

namespace DaydreamLab\User\Services\UserTag\Admin;

use DaydreamLab\User\Repositories\UserTag\Admin\UserTagAdminRepository;
use DaydreamLab\User\Services\UserTag\UserTagService;

class UserTagAdminService extends UserTagService
{
    public function __construct(UserTagAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
