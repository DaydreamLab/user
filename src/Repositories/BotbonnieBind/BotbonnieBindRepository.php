<?php

namespace DaydreamLab\User\Repositories\BotbonnieBind;

use DaydreamLab\User\Models\BotbonnieBind\BotbonnieBind;
use DaydreamLab\User\Repositories\UserRepository;

class BotbonnieBindRepository extends UserRepository
{
    protected $modelName = 'BotbonnieBind';

    public function __construct(BotbonnieBind $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
