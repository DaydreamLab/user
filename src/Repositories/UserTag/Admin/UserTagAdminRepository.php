<?php

namespace DaydreamLab\User\Repositories\UserTag\Admin;

use DaydreamLab\User\Models\UserTag\Admin\UserTagAdmin;
use DaydreamLab\User\Repositories\UserTag\UserTagRepository;
use Illuminate\Support\Collection;

class UserTagAdminRepository extends UserTagRepository
{
    public function __construct(UserTagAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function batchUpdateCategoryId(Collection $input)
    {
        return $this->model
            ->whereIn('id', $input->get('ids'))
            ->update(['categoryId' => $input->get('categoryId')]);
    }
}
