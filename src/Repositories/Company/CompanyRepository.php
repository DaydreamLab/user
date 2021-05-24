<?php

namespace DaydreamLab\User\Repositories\Company;

use DaydreamLab\JJAJ\Repositories\BaseRepository;
use DaydreamLab\User\Models\Company\Company;

class CompanyRepository extends BaseRepository
{
    protected $modelName = 'Company';

    protected $modelType = 'Base';

    public function __construct(Company $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
