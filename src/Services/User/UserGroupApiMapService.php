<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserGroupApiMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class UserGroupApiMapService extends BaseService
{
    protected $type = 'UserGroupApiMap';

    public function __construct(UserGroupApiMapRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function store(Collection $input, $diff = false)
    {
        return parent::storeKeysMap($input);
    }
}
