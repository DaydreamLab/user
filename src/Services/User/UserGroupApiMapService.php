<?php

namespace DaydreamLab\User\Services\User;

use DaydreamLab\User\Repositories\User\UserGroupApiMapRepository;
use DaydreamLab\JJAJ\Services\BaseService;
use Illuminate\Support\Collection;

class UserGroupApiMapService extends BaseService
{
    protected $type = 'UserGroupApiMap';

    public function __construct(UserGroupApiMapRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function add(Collection $input)
    {
        $item = parent::add($input);

        //event(new Add($item, $this->model_name, $input, $this->user));

        return $item;
    }


    public function checkout(Collection $input)
    {
        $result = parent::checkout($input);

        //event(new Checkout($this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function modify(Collection $input)
    {
        $result =  parent::modify($input);

        //event(new Modify($this->find($input->id), $this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function ordering(Collection $input, $orderingKey = 'ordering')
    {
        $result = parent::ordering($input, $orderingKey);

        //event(new Ordering($this->model_name, $result, $input, $orderingKey, $this->user));

        return $result;
    }


    public function remove(Collection $input)
    {
        $result =  parent::remove($input);

        //event(new Remove($this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function state(Collection $input)
    {
        $result = parent::state($input);

        //event(new State($this->model_name, $result, $input, $this->user));

        return $result;
    }
}
