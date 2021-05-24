<?php

namespace DaydreamLab\User\Repositories\Company\Front;

use DaydreamLab\User\Repositories\Company\CompanyRepository;
use DaydreamLab\User\Models\Company\Front\CompanyFront;

class CompanyFrontRepository extends CompanyRepository
{
    protected $modelType = 'Front';

    public function __construct(CompanyFront $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}