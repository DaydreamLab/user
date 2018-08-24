<?php

namespace DaydreamLab\User\Services\Social\Front;

use DaydreamLab\User\Repositories\Social\Front\SocialUserFrontRepository;
use DaydreamLab\User\Services\Social\SocialUserService;

class SocialUserFrontService extends SocialUserService
{
    protected $type = 'SocialUserFront';

    public function __construct(SocialUserFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
