<?php

namespace DaydreamLab\User\Repositories;

use DaydreamLab\JJAJ\Models\BaseModel;
use DaydreamLab\JJAJ\Repositories\BaseRepository;

abstract class UserRepository extends BaseRepository
{
    protected $package = 'User';

    public function __construct(BaseModel $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}