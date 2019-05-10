<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Http\Exceptions\HttpResponseException;
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


    public function getSelfPage()
    {
        $user   = Auth::guard('api')->user();
        $groups = $user->groups;

        $group_apis     = collect();
        $asset_assets   = \Kalnoy\Nestedset\Collection::make();
        foreach ($groups as $group)
        {
            $apis           = $group->api()->get();
            $group_apis     = $group_apis->merge($apis);
            $assets         = $group->asset()->get();
            $asset_assets   = $asset_assets->merge($assets);
        }
        $asset_assets = $asset_assets->toTree();

        $apis = [];
        foreach ($group_apis as $group_api)
        {
            $temp_api_asset_id  = $group_api->asset->id;
            if (!array_key_exists($temp_api_asset_id, $apis))
            {
                $apis[$temp_api_asset_id] = [];
            }
            $apis[$temp_api_asset_id][] = $group_api->method;
        }

        $response['apis']      = $apis;
        $response['assets']    = $asset_assets;

        $this->status = Str::upper(Str::snake($this->type.'GetSelfPageSuccess'));;
        $this->response = $response;

        return $response;

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
        if (InputHelper::null($input, 'id')) {
            $user = $this->checkEmail($input->email);
            if ($user) {
                return false;
            }

            if (InputHelper::null($input, 'password'))
            {
                throw new HttpResponseException(ResponseHelper::genResponse('INPUT_INVALID', (object)['password' => ['password can\'t be null']]));
            }
            else
            {
                $input->put('password', bcrypt($input->password));
                $input->put('activate_token', Str::random(48));
            }
        }
        else
        {
            if (!InputHelper::null($input, 'password'))
            {
                $input->put('password', bcrypt($input->password));
            }
        }

        $input->forget('password_confirmation');


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
