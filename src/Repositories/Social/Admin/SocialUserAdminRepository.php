<?php

namespace DaydreamLab\User\Repositories\Social\Admin;

use DaydreamLab\User\Repositories\Social\SocialUserRepository;
use DaydreamLab\User\Models\Social\Admin\SocialUserAdmin;

class SocialUserAdminRepository extends SocialUserRepository
{
    public function __construct(SocialUserAdmin $model)
    {
        parent::__construct($model);
    }
}