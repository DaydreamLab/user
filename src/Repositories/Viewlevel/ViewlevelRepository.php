<?php

namespace DaydreamLab\User\Repositories\Viewlevel;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Viewlevel\Viewlevel;

class ViewlevelRepository extends BaseRepository
{
    public function __construct(Viewlevel $model)
    {
        parent::__construct($model);
    }
}