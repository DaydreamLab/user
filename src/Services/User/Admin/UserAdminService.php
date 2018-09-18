<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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


    public function block(Collection $input)
    {
        foreach ($input->ids as $key => $id) {
            $user           = $this->find($id);
            $user->block    = $input->block;
            $result         = $user->save();
            if (!$result) {
                break;
            }
        }

        if ($input->block == '1') {
            $action = 'Block';
        }
        elseif ($input->block == '0') {
            $action = 'Unblock';
        }


        if($result) {
            $this->status =  Str::upper(Str::snake($this->type. $action . 'Success'));
        }
        else {
            $this->status =  Str::upper(Str::snake($this->type. $action . 'Fail'));
        }

        $this->response = null;
        return $result;
    }


    public function getApis()
    {
        $user = Auth::guard('api')->user();
        $apis = new Collection();
        foreach ($user->roles as $role) {
            $apis = $apis->merge($role->apis);
        }

        $response = [];
        foreach ($apis as $api) {
            if (!array_key_exists($api->asset_id, $response)) {
                $response[$api->asset_id] = [];
            }
            $response[$api->asset_id][] = $api->method;
        }

        $this->status = Str::upper(Str::snake($this->type.'GetApisSuccess'));;
        $this->response = $response;

        return $apis;
    }


    public function getGrant($id)
    {
        $user       = $this->find($id);
        $redirect   = $user->redirect;
        $roles      = [];
        foreach ($user->roles as $role) {
            $temp = $role->only('id','title', 'state', 'redirect');
            $temp['role_id'] = $temp['id'];
            $temp['user_id'] = $user->id;
            $roles[]         = $temp;
        }

        $data['roles'] = $roles;
        $data['redirect'] = $redirect;
        $this->status = Str::upper(Str::snake($this->type.'GetGrantSuccess'));;
        $this->response = (object)$data;
        return true;
    }


    public function getPage($id = null)
    {
        $id == null ? $user = Auth::guard('api')->user() : $user = $this->find($id);

        $assets = new \Kalnoy\Nestedset\Collection();
        foreach ($user->roles as $role) {
            $assets = $assets->merge($role->assets);
        }

        $tree = $assets->toTree();
        $this->status = Str::upper(Str::snake($this->type.'GetPageSuccess'));;
        $this->response = $tree;

        return $tree;
    }


    public function store(Collection $input)
    {
        if ($input->has('id') ) {
            // 有填密碼
            if(!$input->get('password') == '') {
                $password = $input->get('password');
            }
            $input->forget('password');
            $input->forget('password_confirmation');
            $input->put('password', bcrypt($password));
        }
        else {
            $password = $input->password;
            $input->forget('password');
            $input->put('password', bcrypt($password));
            $input->put('activate_token', Str::random(48));
        }


        if (!$input->has('id')) {
            $user = $this->checkEmail($input->email);
            if ($user) {
                return false;
            }
        }


        $result = parent::store($input);
        if (gettype($result) == 'boolean') {    //更新使用者
            $map = [
                'user_id'=> $input->id,
                'role_ids'=> [$input->role_id]
            ];
        }
        else {
            $map = [
                'user_id'=> $result->id,        //新增使用者
                'role_ids'=> [$input->role_id]
            ];
        }

        $this->userRoleMapAdminService->storeKeysMap(Helper::collect($map));

        return $result;
    }

}
