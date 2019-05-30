<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\AssetGroupMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssetGroupMapService extends BaseService
{
    protected $type = 'AssetGroupMap';

    public function __construct(AssetGroupMapRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input, $diff = false)
    {
        return parent::storeKeysMap($input);
    }
}
