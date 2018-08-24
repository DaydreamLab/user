<?php

namespace DaydreamLab\User\Services\Social;

use DaydreamLab\User\Repositories\Social\SocialUserRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class SocialUserService extends BaseService
{
    protected $type = 'SocialUser';

    public function __construct(SocialUserRepository $repo)
    {
        parent::__construct($repo);
    }
}
