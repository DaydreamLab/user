<?php

namespace DaydreamLab\User\Repositories\Api\Admin;

use DaydreamLab\User\Models\Api\Admin\ApiAdmin;
use DaydreamLab\User\Repositories\Api\ApiRepository;


class ApiAdminRepository extends ApiRepository
{
    public function __construct(ApiAdmin $model)
    {
        parent::__construct($model);
    }
}
