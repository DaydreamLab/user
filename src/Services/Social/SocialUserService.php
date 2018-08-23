<?php

namespace App\Services\Social;

use App\Repositories\Social\SocialUserRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class SocialUserService extends BaseService
{
    protected $type = 'SocialUser';

    public function __construct(SocialUserRepository $repo)
    {
        parent::__construct($repo);
    }
}
