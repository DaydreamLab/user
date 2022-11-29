<?php

namespace DaydreamLab\User\Services\Company\Front;

use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Repositories\Company\Front\CompanyFrontRepository;
use DaydreamLab\User\Services\Company\CompanyService;
use Illuminate\Support\Collection;

class CompanyFrontService extends CompanyService
{
    protected $modelType = 'Front';

    public function __construct(CompanyFrontRepository $repo)
    {
        parent::__construct($repo);
        $this->repo = $repo;
    }


    public function getInfo($vat)
    {
        $company = $this->findBy('vat', '=', $vat)->first();

        $this->status = $company ? 'GetItemSuccess' : 'ItemNotExist';
        $this->response = $company;

        return $this->response;
    }
}
