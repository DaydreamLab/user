<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\User\UserGroupRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class UserGroupService extends BaseService
{
    use NestedServiceTrait;

    protected $type = 'UserGroup';

    public function __construct(UserGroupRepository $repo)
    {
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        return $this->storeNested($input);
    }
}
