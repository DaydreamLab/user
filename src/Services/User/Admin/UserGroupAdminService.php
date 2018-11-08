<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\NestedServiceTrait;
use DaydreamLab\User\Repositories\User\Admin\UserGroupAdminRepository;
use DaydreamLab\User\Services\User\UserGroupService;
use DaydreamLab\User\Services\Viewlevel\Admin\ViewlevelAdminService;
use Illuminate\Support\Collection;

class UserGroupAdminService extends UserGroupService
{
    use NestedServiceTrait {
        addNested as traitAddNested;
    }

    protected  $viewlevelAdminService;

    protected $type = 'UserGroupAdmin';


    public function __construct(UserGroupAdminRepository $repo, ViewlevelAdminService $viewlevelAdminService)
    {
        $this->viewlevelAdminService = $viewlevelAdminService;
        parent::__construct($repo);
    }


    public function addNested(Collection $input)
    {
        $item = $this->traitAddNested($input);
        $super_user = $this->viewlevelAdminService->findBy('title', '=', 'Super User')->first();
        if ($super_user)
        {
            $rules = $super_user->rules;
            $rules[] = $item->id;
            $super_user->rules = $rules;
            $super_user->save();
        }
        return $item;
    }
}
