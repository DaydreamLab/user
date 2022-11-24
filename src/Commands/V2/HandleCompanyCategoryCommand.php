<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\Company\CompanyCategory;
use Illuminate\Console\Command;

class HandleCompanyCategoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:company-category-handle';

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
        $this->info('更新公司分類...');
        $this->handleCompanyCategory();
        $this->info('更新公司分類完成');
    }


    public function handleCompanyCategory()
    {
        CompanyCategory::whereIn('title', ['原廠', '競爭廠商', '零壹員工'])
            ->get()
            ->each(function ($company) {
                $company->state = -1;
                $company->save();
            });
    }
}
