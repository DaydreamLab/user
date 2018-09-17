<?php

namespace DaydreamLab\User\Repositories\Viewlevel\Front;

use DaydreamLab\User\Repositories\Viewlevel\ViewlevelRepository;
use DaydreamLab\User\Models\Viewlevel\Front\ViewlevelFront;

class ViewlevelFrontRepository extends ViewlevelRepository
{
    public function __construct(ViewlevelFront $model)
    {
        parent::__construct($model);
    }
}