<?php

namespace DaydreamLab\User\Repositories\Line;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Line\Line;

class LineRepository extends BaseRepository
{
    protected $modelName = 'Line';

    public function __construct(Line $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
