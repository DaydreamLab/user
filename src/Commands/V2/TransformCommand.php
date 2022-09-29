<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\Company\Company;
use Illuminate\Console\Command;

class TransformCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:v2-transform';

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
        $this->call('migrate');

        $this->info('更新統編資料中...');
        $this->transformCompanies();
        $this->info('更新統編資料完成');
    }


    public function transformCompanies()
    {
        $companies = Company::with('category')->get();
        foreach ($companies as $company) {
            if (in_array($company->category->title, ['經銷會員', '零壹員工'])) {
                $company->status = EnumHelper::COMPANY_APPROVE;
                $company->approvedAt = $company->created_at;
            } elseif (in_array($company->category->title, ['原廠', '競爭廠商'])) {
                $company->status = EnumHelper::COMPANY_NONE;
                $company->rejectedAt = null;
            } else {
                $company->status = EnumHelper::COMPANY_NEW;
            }

            if (!isset($company->mailDomains['domain'])) {
                $data = ['domain' => $company->mailDomains, 'email' => []];
                $company->mailDomains = $data;
            }

            $company->save();
        }
    }
}
