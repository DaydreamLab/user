<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\Company\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class ClearCompanyWithoutMembersJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->queue = 'import-job';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companies = Company::whereDoesntHave('userCompanies')
            ->whereNotIn('categoryNote', ['原廠', '競業'])
            ->where('category_id', 5)
            ->get();
        Log::info('刪除無會員公司：' . $companies->count() . '筆');
        foreach ($companies as $company) {
            Log::info('公司名稱:' . $company->name . ' 公司統編:' . $company->vat);
            $company->delete();
        }
    }
}
