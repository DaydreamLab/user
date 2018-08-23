<?php

namespace App\Repositories\Social\Front;

use App\Repositories\Social\SocialUserRepository;
use App\Models\Social\Front\SocialUserFront;

class SocialUserFrontRepository extends SocialUserRepository
{
    public function __construct(SocialUserFront $model)
    {
        parent::__construct($model);
    }
}