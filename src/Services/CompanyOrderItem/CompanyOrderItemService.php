<?php

namespace DaydreamLab\User\Services\CompanyOrderItem;

use DaydreamLab\User\Repositories\CompanyOrderItem\CompanyOrderItemRepository;
use DaydreamLab\User\Services\UserService;
use Illuminate\Support\Collection;

class CompanyOrderItemService extends UserService
{
    protected $modelName = 'CompanyOrderItem';

    public function __construct(CompanyOrderItemRepository $repo)
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


    public function featured(Collection $input)
    {
        $item = parent::featured($input);

        //event(new Add($item, $this->model_name, $input, $this->user));

        return $item;
    }


    public function featuredOrdering(Collection $input)
    {
        $item = parent::featuredOrdering($input);

        //event(new Add($item, $this->model_name, $input, $this->user));

        return $item;
    }


    public function modify(Collection $input)
    {
        $result =  parent::modify($input);

        //event(new Modify($this->find($input->id), $this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function ordering(Collection $input)
    {
        $result = parent::ordering($input);

        //event(new Ordering($this->model_name, $result, $input, $orderingKey, $this->user));

        return $result;
    }


    public function orderingNested(Collection $input)
    {
        $result = parent::orderingNested($input);

        //event(new Ordering($this->model_name, $result, $input, $orderingKey, $this->user));

        return $result;
    }


    public function remove(Collection $input)
    {
        $result =  parent::remove($input);

        //event(new Remove($this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function restore(Collection $input)
    {
        $result = parent::restore($input);

        //event(new Checkout($this->model_name, $result, $input, $this->user));

        return $result;
    }


    public function state(Collection $input)
    {
        $result = parent::state($input);

        //event(new State($this->model_name, $result, $input, $this->user));

        return $result;
    }
}
