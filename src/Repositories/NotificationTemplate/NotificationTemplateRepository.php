<?php

namespace DaydreamLab\User\Repositories\NotificationTemplate;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\NotificationTemplate\NotificationTemplate;

class NotificationTemplateRepository extends BaseRepository
{
    protected $modelName = 'NotificationTemplate';

    public function __construct(NotificationTemplate $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
