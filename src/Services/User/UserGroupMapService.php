<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\User\UserGroupMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class UserGroupMapService extends BaseService
{
    protected $type = 'UserGroupMap';

    public function __construct(UserGroupMapRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input, $diff = false)
    {
        return parent::storeKeysMap($input);
    }
}
