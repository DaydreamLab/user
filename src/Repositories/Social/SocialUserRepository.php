<?php

namespace DaydreamLab\User\Repositories\Social;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Social\SocialUser;

class SocialUserRepository extends BaseRepository
{
    public function __construct(SocialUser $model)
    {
        parent::__construct($model);
    }
}