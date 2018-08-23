<?php

namespace App\Services\Social\Admin;

use App\Repositories\Social\Admin\SocialUserAdminRepository;
use App\Services\Social\SocialUserService;

class SocialUserAdminService extends SocialUserService
{
    protected $type = 'SocialUserAdmin';

    public function __construct(SocialUserAdminRepository $repo)
    {
        parent::__construct($repo);
    }
}
