<?php

namespace DaydreamLab\User\Services\Api;

use DaydreamLab\User\Repositories\Api\ApiRepository;
use DaydreamLab\JJAJ\Services\BaseService;

class ApiService extends BaseService
{
    protected $package = 'User';

    protected $modelName = 'Asset';

    protected $modelType = 'Base';

    public function __construct(ApiRepository $repo)
    {
        parent::__construct($repo);
    }

}
