<?php

namespace App\Repositories\Social;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use App\Models\Social\SocialUser;

class SocialUserRepository extends BaseRepository
{
    public function __construct(SocialUser $model)
    {
        parent::__construct($model);
    }
}