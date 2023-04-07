<?php

namespace DaydreamLab\User\Repositories\User;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\User\UserCompany;

class UserCompanyRepository extends BaseRepository
{
    public function __construct(UserCompany $model)
    {
        parent::__construct($model);
    }
}