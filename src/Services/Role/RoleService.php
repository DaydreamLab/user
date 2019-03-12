<?php

namespace DaydreamLab\User\Services\Role;

use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\Role\RoleRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RoleService extends BaseService
{
    use NestedServiceTrait;

    protected $type = 'Role';

    public function __construct(RoleRepository $repo)
    {
        parent::__construct($repo);
    }



    public function store(Collection $input)
    {
        return $this->storeNested($input);
    }


    public function remove(Collection $input)
    {
        return $this->removeNested($input);
    }
}
