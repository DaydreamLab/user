<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAdminService extends UserService
{
    protected $type = 'UserAdmin';

    protected $userGroupMapAdminService;

    protected $search_keys = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $eagers = [
        //'usergroup',
        //'roles'
    ];

    public function __construct(UserAdminRepository $repo,
                                UserGroupMapAdminService $userGroupMapAdminService)
    {
        parent::__construct($repo);
        $this->userGroupMapAdminService = $userGroupMapAdminService;
    }


    public function block(Collection $input)
    {
        $result = false;
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

        event(new Block($this->model_name, $result, $input, $this->user));

        if($result) {
            $this->status =  Str::upper(Str::snake($this->type. $action . 'Success'));
        }
        else {
            $this->status =  Str::upper(Str::snake($this->type. $action . 'Fail'));
        }

        $this->response = null;


        return $result;
    }


    public function getAction()
    {
        $user = Auth::guard('api')->user();
        $groups = $user->groups;

        $response = [];
        foreach ($groups as $group)
        {
            $group_apis      = $group->apis;

            $assets = [];
            foreach ($group_apis as $group_api) {
                $temp_api           = $group_api->only('id', 'method');
                $temp_api['name']   = $temp_api['method'];

                $temp_asset             = $group_api->asset->only('id', 'title');
                $temp_asset['disabled'] = true;
                $temp_asset['child']    = [];
                if(!in_array($temp_asset, $assets)) {
                    $assets[]   = $temp_asset;
                    $response[] = $temp_asset;
                }

                foreach ($response as $key => $item) {
                    if ($item['id'] == $temp_asset['id'] && !in_array($temp_api, $item['child'])) {
                        $response[$key]['child'][] = $temp_api;
                    }
                }
            }
        }


        $this->status = Str::upper(Str::snake($this->type.'GetActionSuccess'));;
        $this->response = $response;

        return $response;
    }



    public function getApis()
    {
        $user = Auth::guard('api')->user();
        $apis = new Collection();
        foreach ($user->groups as $group) {
            $apis = $apis->merge($group->apis);
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


    public function getPage($id = null)
    {
        $id == null ? $user = Auth::guard('api')->user() : $user = $this->find($id);

        $assets = new \Kalnoy\Nestedset\Collection();
        foreach ($user->groups as $group) {
            $assets = $assets->merge($group->assets);
        }

        $tree = $assets->toTree();
        $this->status = Str::upper(Str::snake($this->type.'GetPageSuccess'));;
        $this->response = $tree;

        return $tree;
    }

    public function search(Collection $input)
    {
        $input_groups = $input->get('groups');
        $input->forget('groups');

        if (!$this->user->isSuperUser())
        {
            $input->put('where', [
                [
                    'key'       => 'email',
                    'operator'  => '!=',
                    'value'     => 'admin@daydream-lab.com'
                ]
            ]);
        }

        $search_result = parent::search($input);

        //
        $items = new Collection();
        foreach ($search_result as $user)
        {
            foreach ($user->usergroup as $group)
            {
                if ($input_groups == '')
                {
                    if (in_array($group->id, $this->user->viewlevels))
                    {
                        $items->push($user);
                        break;
                    }
                }
                else
                {
                    if($input_groups == $group->id)
                    {
                        $items->push($user);
                        break;
                    }
                }
            }
        }

        $items  = $this->repo->paginate($items, $input->get('limit'));
        $this->response = $items;

        return $items;
    }


    public function store(Collection $input)
    {
        if ($input->has('id') ) {
            // 有填密碼
            if($input->get('password') != '') {
                $password = $input->get('password');
                $input->forget('password');
                $input->forget('password_confirmation');
                $input->put('password', bcrypt($password));
            }
        }
        else {
            $password = $input->password;
            $input->forget('password');
            $input->forget('password_confirmation');
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
            $group_map = [
                'user_id'   => $input->id,        //新增使用者
                'group_ids'  => $input->group_ids
            ];
        }
        else {
            $group_map = [
                'user_id'   => $result->id,
                'group_ids'  => $input->group_ids
            ];
        }

        $this->userGroupMapAdminService->storeKeysMap(Helper::collect($group_map));

        return $result;
    }

}
