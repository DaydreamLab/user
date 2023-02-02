<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Company\Company;
use Illuminate\Console\Command;

class TransformCompanyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:company-transform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install DaydreamLab user component';

    protected $constants = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('更新公司資料...');
        $this->transformCompanies();
        $this->info('更新公司資料完成');
    }


    public function transformCompanies()
    {
        $companies = Company::with(['category', 'userCompanies'])->get();
        foreach ($companies as $company) {
            if ($company->category->title == EnumHelper::COMPANY_CATEGORY_DEALER) {
                $company->approvedAt = $company->created_at;
            }

            if (
                is_array($company->mailDomains)
                && ($company->domain && !in_array($company->domain, $company->mailDomains ?: []))
            ) {
                $mailDomains = $company->mailDomains;
                $mailDomains[] = $company->domain;
                $data = ['domain' => $mailDomains, 'email' => []];

            } else {
                if ($company->domain) {
                    $data = ['domain' => [$company->domain], 'email' => []];
                } else {
                    $data = ['domain' => [], 'email' => []];
                }
            }
            $company->mailDomains = $data;

            $company->industry = $company->userCompanies->filter(function ($userCompany) {
                return !in_array($userCompany->industry, ['', null]);
            })->pluck('industry')->unique()->values()->except(['', null])->all() ?: [];
            $company->timestamps = false;
            $company->save();
        }
    }
}
