<?php

namespace DaydreamLab\User\Repositories\Viewlevel\Admin;

use DaydreamLab\User\Repositories\Viewlevel\ViewlevelRepository;
use DaydreamLab\User\Models\Viewlevel\Admin\ViewlevelAdmin;

class ViewlevelAdminRepository extends ViewlevelRepository
{
    public function __construct(ViewlevelAdmin $model)
    {
        parent::__construct($model);
    }
}