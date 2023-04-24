<?php

namespace DaydreamLab\User\Repositories\Company\Admin;

use DaydreamLab\Cms\Models\Item\Item;
use DaydreamLab\JJAJ\Database\QueryCapsule;
use DaydreamLab\JJAJ\Exceptions\ForbiddenException;
use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Repositories\Company\CompanyRepository;
use DaydreamLab\User\Models\Company\Admin\CompanyAdmin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
            ->whereJsonContains('mailDomains->domain', [$domain])
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


    public function search(Collection $data)
    {
        $q = $data->get('q');
        if ($inputIndustry = $data->pull('company_industry')) {
            $industry = Item::where('id', $inputIndustry)->first();
            if ($industry) {
                $name = ($industry->title);
                $q->whereJsonContains('industry', [$name]);
            }
        }

        $categoryNote = $data->get('categoryNote');
        if ($data->has('categoryNote') && $categoryNote) {
            $q->where('categoryNote', $categoryNote);
        }
        $data->forget('categoryNote');

        $haveMembers = $data->pull('haveMembers');
        if ($haveMembers) {
            $companies = (new QueryCapsule())
                ->select('company_id')
                ->whereNotNull('company_id')
                ->exec(new UserCompany());
            if ($haveMembers === '無') {
//                $q->whereDoesntHave('userCompanies');
                $q->whereNotIn('id', $companies->pluck('company_id')->all());
            } elseif ($haveMembers === '有') {
//                $q->whereHas('userCompanies');
                $q->whereIn('id', $companies->pluck('company_id')->all());
            }
        }

        $q->with('userCompanies');
        $data->put('q', $q);

        return parent::search($data);
    }
}
