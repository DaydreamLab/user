<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\User\Repositories\Role\RoleApiMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class RoleApiMapService extends BaseService
{
    protected $type = 'RoleAssetApiMap';

    public function __construct(RoleApiMapRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        return parent::storeKeysMap($input);
    }
}
