<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\User\UserCompany;
use DaydreamLab\User\Notifications\User\UserCompanyEmailVerificationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class DailyDealerCheckJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('batch-job');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userCompanies = UserCompany::whereHas('company.category', function ($q) {
            $q->whereIn('companies_categories.title', ['零壹員工', '經銷會員']);
        })->where(function ($q) {
            $q->where('validated', 1)
                ->where(function ($q) {
                    $threeMonthAgo = now()->subMonths(3)->toDateTimeString();
                    $q->whereNull('lastValidate')
                        ->orWhere('lastValidate', '<', $threeMonthAgo);
                });
        })->get();

        foreach ($userCompanies as $userCompany) {
            $userCompany->isExpired = 1;
            $userCompany->validateToken = Str::random(128);
            $userCompany->save();
            Notification::route('mail', $userCompany->user->email)
                ->notify(new UserCompanyEmailVerificationNotification($userCompany->user));
        }
    }
}
