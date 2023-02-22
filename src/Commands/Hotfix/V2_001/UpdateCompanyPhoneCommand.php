<?php

namespace DaydreamLab\User\Commands\Hotfix\V2_001;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Company\Company;
use Illuminate\Console\Command;

class UpdateCompanyPhoneCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-company-phone';

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
        $this->info('更新公司電話...');

        $companies = Company::whereHas('userCompanies')
            ->with('userCompanies')
            ->get();


        foreach ($companies as $company) {
            $original = $company->phones;
            $phoneData = collect($company->phones);
            foreach ($company->userCompanies as $userCompany) {
                if (count($userCompany->phones ?: [])) {
                    foreach ($userCompany->phones ?: [] as $userCompanyPhone) {
                        if (!in_array($userCompanyPhone['phone'], $phoneData->pluck('phone')->all())) {
                            $phoneData->push([
                                'phoneCode' => $userCompanyPhone['phoneCode'],
                                'phone' => $userCompanyPhone['phone'],
                                'ext'   => ''
                            ]);
                        }
                    }
                }
            }
            $company->phones = $phoneData;
            $company->save();
        }

        $this->info('更新公司電話完成');
    }
}
