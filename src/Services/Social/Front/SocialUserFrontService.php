<?php

namespace App\Services\Social\Front;

use App\Repositories\Social\Front\SocialUserFrontRepository;
use App\Services\Social\SocialUserService;

class SocialUserFrontService extends SocialUserService
{
    protected $type = 'SocialUserFront';

    public function __construct(SocialUserFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
