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


    public function getCompanyByEmailDomain($email)
    {
        $domain = explode('@', $email)[1];
        $companies = $this->model
            ->where('mailDomains', 'like', '%' . $domain . '%')
            ->get();

        return $companies->filter(function ($company) use ($domain, $email) {
            return in_array($domain, $company->mailDomains['domain'])
                || array($email, $company->mailDomains['email']);
        });
    }


    public function getCompanyByCompanyData($companyData)
    {
        return $this->model
                ->where('vat', $companyData['vat'])
                ->first();
    }
}
