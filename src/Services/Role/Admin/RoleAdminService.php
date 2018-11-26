<?php

namespace DaydreamLab\User\Services\Role\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Role\Admin\RoleAdminRepository;
use DaydreamLab\User\Services\Role\RoleService;

class RoleAdminService extends RoleService
{
    protected $type = 'RoleAdmin';

    public function __construct(RoleAdminRepository $repo)
    {
        parent::__construct($repo);
    }

    public function getTree()
    {
        $result = parent::getTree();

        $data = [];
        foreach ($result as $item)
        {
            if ($item->title != 'Super User')
            {
                $data[] = $item;
            }
        }

        $this->response = $data;

        return $data;
    }

}
