<?php

namespace DaydreamLab\User\Repositories\Api\Front;

use DaydreamLab\User\Repositories\Api\ApiRepository;
use DaydreamLab\User\Models\Api\Front\ApiFront;

class ApiFrontRepository extends ApiRepository
{
    public function __construct(ApiFront $model)
    {
        parent::__construct($model);
    }
}
