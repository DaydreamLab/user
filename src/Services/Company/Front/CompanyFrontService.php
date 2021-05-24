<?php

namespace DaydreamLab\User\Services\Company\Front;

use DaydreamLab\User\Repositories\Company\Front\CompanyFrontRepository;
use DaydreamLab\User\Services\Company\CompanyService;

class CompanyFrontService extends CompanyService
{
    protected $modelType = 'Front';

    public function __construct(CompanyFrontRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }
}
