<?php

namespace DaydreamLab\User\Repositories\User\Front;

use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\User\Models\User\Front\UserFront;

class UserFrontRepository extends UserRepository
{
    public function __construct(UserFront $model)
    {
        parent::__construct($model);
    }


    public function findDealerTokenUser($token)
    {
        return $this->model->whereHas('company', function ($q) use ($token) {
            $q->where('users_companies.validateToken', $token);
        })->first();
    }
}