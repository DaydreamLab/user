<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Repositories\User\Admin\UserRoleMapAdminRepository;
use DaydreamLab\User\Services\User\UserRoleMapService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserRoleMapAdminService extends UserRoleMapService
{
    protected $type = 'UserRoleMapAdmin';

    protected $userAdminRepo;

    public function __construct(UserRoleMapAdminRepository $repo,
                                UserAdminRepository $userAdminRepo)
    {
        $this->userAdminRepo = $userAdminRepo;
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        $user = $this->userAdminRepo->find($input->user_id);
        $user->redirect = $input->redirect;
        $user->save();
        $input->forget('redirect');

        return parent::storeKeysMap($input);
    }
}
