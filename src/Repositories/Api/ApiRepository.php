<?php

namespace DaydreamLab\User\Repositories\Api;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Api\Api;

class ApiRepository extends BaseRepository
{
    public function __construct(Api $model)
    {
        parent::__construct($model);
    }
}
