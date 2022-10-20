<?php

namespace DaydreamLab\User\Services;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

abstract class UserService extends BaseService
{
    protected $package = 'User';

    public function __construct(BaseRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
