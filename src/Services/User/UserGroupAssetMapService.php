<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserGroupAssetMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class UserGroupAssetMapService extends BaseService
{
    protected $type = 'UserGroupAssetMap';

    public function __construct(UserGroupAssetMapRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function store(Collection $input)
    {
        return parent::storeKeysMap($input);
    }
}
