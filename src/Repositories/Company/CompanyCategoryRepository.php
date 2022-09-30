<?php

namespace DaydreamLab\User\Repositories\Company;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Company\CompanyCategory;

class CompanyCategoryRepository extends BaseRepository
{
    protected $modelName = 'CompanyCategory';

    protected $modelType = 'Base';

    public function __construct(CompanyCategory $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
