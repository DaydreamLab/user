<?php

namespace DaydreamLab\User\Services\User\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Helpers\InputHelper;
use DaydreamLab\JJAJ\Helpers\ResponseHelper;
use DaydreamLab\JJAJ\Traits\LoggedIn;
use DaydreamLab\User\Events\Block;
use DaydreamLab\User\Repositories\User\Admin\UserAdminRepository;
use DaydreamLab\User\Services\User\UserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserAdminService extends UserService
{
    use LoggedIn;

    protected $modelType = 'Admin';

    protected $search_keys = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $eagers = [
        //'groups',
        //'viewlevels'
    ];

    public function __construct(UserAdminRepository $repo)
    {
        parent::__construct($repo);
    }


    public function block(Collection $input)
    {
        $result = false;
        foreach ($input->get('ids') as $key => $id) {
            $user           = $this->find($id);
            $result         = $this->repo->update([
                'block' => $input->get('block')
            ], $user);
            if (!$result) {
                break;
            }
        }

        $block = $input->get('block');
        if ($block== '1') {
            $action = 'Block';
        } elseif ($block == '0') {
            $action = 'Unblock';
        }

        event(new Block($this->getServiceName(), $result, $input, $this->user));

        if($result) {
            $this->status = $action. 'Success';
        } else {
            $this->status = $action. 'Fail';
        }

        return $result;
    }


    public function getSelfPage()
    {
        $user   = $this->getUser();
        $groups = $user->groups;

        $group_apis     = collect();
        $asset_assets   = \Kalnoy\Nestedset\Collection::make();
        foreach ($groups as $group)
        {
            $apis           = $group->apis()->get();
            $group_apis     = $group_apis->merge($apis);
            $assets         = $group->assets()->get();
            $asset_assets   = $asset_assets->merge($assets);
        }
        $asset_assets = $asset_assets->toTree();

        $apis = [];
        foreach ($group_apis as $group_api)
        {
            $temp_api_assets =  $group_api->assets()->get();
            foreach ($temp_api_assets as $temp_api_asset)
            {
                $temp_api_asset_id  = $temp_api_asset->id;
                if (!array_key_exists($temp_api_asset_id, $apis))
                {
                    $apis[$temp_api_asset_id] = [];
                }
                $apis[$temp_api_asset_id][] = $group_api->method;
            }
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
            foreach ($user->groups as $group)
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

            if (InputHelper::null($input, 'password'))
            {
                throw new HttpResponseException(ResponseHelper::genResponse('INPUT_INVALID', (object)['password' => ['password can\'t be null']]));
            }
            else
            {
                if ($this->checkEmail($input->get('email'))){
                    throw new HttpResponseException(
                        ResponseHelper::genResponse(
                            $this->status)
                    );
                }
                $input->put('password', bcrypt($input->get('password')));
                $input->put('activate_token', Str::random(48));
            }
        }
        else
        {
            if (!InputHelper::null($input, 'password'))
            {
                $input->put('password', bcrypt($input->get('password')));
            }
            else
            {
                $input->forget('password');
            }
        }

        $input->forget('password_confirmation');


        $result = parent::store($input);
        if (gettype($result) == 'boolean') {    //更新使用者
            $user = $this->find($input->get('id'));
            $user->groups()->detach();
            $user->groups()->attach($input->get('group_ids'));
        }
        else {//新增使用者
            $result->groups()->attach($input->get('group_ids'));
        }

        return $result;
    }

}
