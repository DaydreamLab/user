<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserAdminService extends UserService
{
    protected $type = 'UserAdmin';

    protected $userRoleMapAdminService;

    public function __construct(UserAdminRepository $repo,
                                UserRoleMapAdminService $userRoleMapAdminService)
    {
        $this->userRoleMapAdminService = $userRoleMapAdminService;
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        if ($input->has('id') ) {
            // 有填密碼
            if(!$input->get('password') == '') {
                $result = parent::changePassword($input);
                if (!$result) {
                    return false;
                }
            }
            else {
                $input->forget('password');
                $input->forget('password_confirmation');
            }
        }
        else {
            $password = $input->password;
            $input->forget('password');
            $input->put('password', bcrypt($password));
            $input->put('activate_token', Str::random(48));
        }

        $map = [];
        $result = parent::store($input);
        if (gettype($result) == 'boolean') {
            $map = [
                'user_id'=> $input->id,
                'role_ids'=> [$input->role_id]
            ];
        }
        else {
            $map = [
                'user_id'=> $result->id,
                'role_ids'=> [$input->role_id]
            ];
        }
        $this->userRoleMapAdminService->storeKeysMap(Helper::collect($map));

        return $result;
    }
}
