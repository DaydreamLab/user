<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserTag;

class UserTagRepository extends BaseRepository
{
    protected $package = 'User';

    protected $modelName = 'UserTag';

    protected $modelType = 'Base';

    public function __construct(UserTag $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}