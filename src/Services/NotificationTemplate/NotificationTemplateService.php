<?php

namespace DaydreamLab\User\Services\NotificationTemplate;

use DaydreamLab\User\Repositories\NotificationTemplate\NotificationTemplateRepository;
use DaydreamLab\User\Services\UserService;
use Illuminate\Support\Collection;

class NotificationTemplateService extends UserService
{
    protected $modelName = 'NotificationTemplate';

    public function __construct(NotificationTemplateRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
