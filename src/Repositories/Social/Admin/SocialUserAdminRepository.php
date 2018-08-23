<?php

namespace App\Repositories\Social\Admin;

use App\Repositories\Social\SocialUserRepository;
use App\Models\Social\Admin\SocialUserAdmin;

class SocialUserAdminRepository extends SocialUserRepository
{
    public function __construct(SocialUserAdmin $model)
    {
        parent::__construct($model);
    }
}