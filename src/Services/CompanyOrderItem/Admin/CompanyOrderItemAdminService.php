<?php

namespace DaydreamLab\User\Services\CompanyOrderItem\Admin;

use DaydreamLab\User\Repositories\CompanyOrderItem\Admin\CompanyOrderItemAdminRepository;
use DaydreamLab\User\Services\CompanyOrderItem\CompanyOrderItemService;

class CompanyOrderItemAdminService extends CompanyOrderItemService
{
    public function __construct(CompanyOrderItemAdminRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
