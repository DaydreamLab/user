<?php

namespace DaydreamLab\User\Repositories\User\Front;

use DaydreamLab\User\Repositories\User\UserCompanyRepository;
use DaydreamLab\User\Repositories\User\UserRepository;
use DaydreamLab\User\Models\User\Front\UserFront;

class UserFrontRepository extends UserRepository
{
    protected $userCompanyRepo;

    public function __construct(UserFront $model, UserCompanyRepository $userCompanyRepo)
    {
        parent::__construct($model);
        $this->userCompanyRepo = $userCompanyRepo;
    }


    public function findDealerTokenUser($token)
    {
        $userCompany = $this->userCompanyRepo->findBy('validateToken', '=', $token)->first();
        if (!$userCompany || !in_array($userCompany->company->category->title, ['經銷會員', '零壹員工'])) {
            return false;
        }

        return $userCompany->user;
    }
}