<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class RoleService extends BaseService
{
    protected $type = 'Role';

    public function __construct(RoleRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        return parent::storeNested($input);
    }
}
