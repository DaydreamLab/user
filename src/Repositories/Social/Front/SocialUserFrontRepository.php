<?php

namespace DaydreamLab\User\Repositories\Social\Front;

use DaydreamLab\User\Repositories\Social\SocialUserRepository;
use DaydreamLab\User\Models\Social\Front\SocialUserFront;

class SocialUserFrontRepository extends SocialUserRepository
{
    public function __construct(SocialUserFront $model)
    {
        parent::__construct($model);
    }
}