<?php

namespace DaydreamLab\User\Services\Api\Front;

use DaydreamLab\User\Repositories\Api\Front\ApiFrontRepository;
use DaydreamLab\User\Services\Api\ApiService;

class ApiFrontService extends ApiService
{
    protected $type = 'AssetApiFront';

    public function __construct(ApiFrontRepository $repo)
    {
        parent::__construct($repo);
    }
}
