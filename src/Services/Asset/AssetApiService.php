<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\User\Repositories\Asset\AssetApiRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class AssetApiService extends BaseService
{
    protected $type = 'AssetApi';

    public function __construct(AssetApiRepository $repo)
    {
        parent::__construct($repo);
    }

}
