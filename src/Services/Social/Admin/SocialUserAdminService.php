<?php

namespace DaydreamLab\User\Services\Social\Admin;

use DaydreamLab\User\Repositories\Social\Admin\SocialUserAdminRepository;
use DaydreamLab\User\Services\Social\SocialUserService;

class SocialUserAdminService extends SocialUserService
{
    protected $type = 'SocialUserAdmin';

    public function __construct(SocialUserAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
