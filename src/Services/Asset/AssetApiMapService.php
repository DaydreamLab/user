<?php

namespace DaydreamLab\User\Services\Asset;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\AssetApiMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssetApiMapService extends BaseService
{
    protected $type = 'AssetApiMap';

    public function __construct(AssetApiMapRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        return parent::storeKeysMap($input);
    }
}
