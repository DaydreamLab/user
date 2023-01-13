<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Models\Company\Company;
use Illuminate\Console\Command;

class TransformCompanyCategoryNoteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:company-category-note-transform';

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
        $this->info('更新公司分類註記...');
        $this->transformUserCategoryNote();
        $this->info('更新公司分類註記完成');
    }


    public function transformUserCategoryNote()
    {
        $companies = Company::with('category')->get();
        foreach ($companies as $company) {
            if ($company->category->title == '原廠') {
                $company->category_id = 5;
                $company->categoryNote = $company->category->title;
            } elseif ($company->category->title == '競爭廠商') {
                $company->category_id = 5;
                $company->categoryNote = '競業';
            } elseif ($company->category->title == '一般') {
                $company->category_id = 5;
                $company->categoryNote = '無';
            } elseif ($company->category->title == '經銷會員') {
                $company->category_id = 3;
                $company->categoryNote = '無';
            } elseif ($company->category->title == '零壹員工') {
                $company->category_id = 3;
                $company->categoryNote = '員工';
            }
            $company->timestamps = false;
            $company->save();
        }
    }
}
