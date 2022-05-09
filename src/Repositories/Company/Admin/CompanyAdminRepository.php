<?php

namespace DaydreamLab\User\Repositories\Company\Admin;

use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Repositories\Company\CompanyRepository;
use DaydreamLab\User\Models\Company\Admin\CompanyAdmin;

class CompanyAdminRepository extends CompanyRepository
{
    protected $modelType = 'Admin';

    public function __construct(CompanyAdmin $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }


    public function getCompanyByEmailDomain($email, $companyData)
    {
        $companies = $this->model
            ->where('domain', explode('@', $email)[1])
            ->get();

        $targetCompany = $companies->filter(function ($company) use ($companyData) {
            return $companyData['name'] == $company->name;
        })->values()->first();

        return $targetCompany;
    }


    public function getCompanyByCompanyData($companyData)
    {
        return $this->model
                ->where('vat', $companyData['vat'])
                ->first();
    }
}