<?php

namespace DaydreamLab\User\Commands\Hotfix\V2_001;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Company\Company;
use Illuminate\Console\Command;

class ClearCompanyMembersIsEmptyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:clear-company-member-empty';

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
        $this->info('清除一般公司並且會員人數為0...');

        $companies = Company::whereDoesntHave('userCompanies')
//            ->whereNotIn('categoryNote', ['原廠', '競業'])
            ->where('category_id', 5)
            ->get();

//        show($companies->map(function ($company) {
//            return array_values($company->only('name', 'vat'));
//        })->all());

        Helper::exportXlsx(['名稱', '統編', '公司標記'], $companies->map(function ($company) {
            return array_values($company->only('name', 'vat', 'categoryNote'));
        })->all(), '1234.xlsx');
        show($companies->count());

        $this->info('清除一般公司並且會員人數為0完成');
    }
}
