<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Role\RoleAssetMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RoleAssetMapService extends BaseService
{
    protected $type = 'RoleAssetMap';

    public function __construct(RoleAssetMapRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        return parent::storeKeysMap($input);
    }
}
