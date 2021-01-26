<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\User\Repositories\Asset\AssetApiRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class AssetApiService extends BaseService
{
    protected $package = 'User';

    protected $modelName = 'Asset';

    protected $modelType = 'Base';

    public function __construct(AssetApiRepository $repo)
    {
        parent::__construct($repo);
    }

}
