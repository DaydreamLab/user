<?php

namespace DaydreamLab\User\Listeners;

use DaydreamLab\Cms\Services\NewsletterSubscription\Front\NewsletterSubscriptionFrontService;
use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Notifications\User\UserCompanyEmailVerificationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Notification;

use function Symfony\Component\String\s;

class UpdateCompanyUsersUserGroupAndEdm implements ShouldQueue
{
    public $queue = 'batch-job';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle(UpdateCompanyUsersUserGroupAndEdmEvent $event)
    {
        $company = $event->company;
        $companyUserGroup = $event->companyUserGroup;
        foreach ($company->userCompanies as $userCompany) {
            if ($user = $userCompany->user) {
                # 這邊要考慮管理員同時擁有經銷商資格
                $original = $user->groups->pluck('id');
                $adminGroupIds = $original->reject(function ($o) {
                    return in_array($o, [6,7]);
                })->values()->all();
                $adminGroupIds[] = $companyUserGroup->id;
                $user->groups()->sync($adminGroupIds);

                if ($company->categoryNote == EnumHelper::COMPANY_NOTE_BLACKLIST) {
                    $user->block = 1;
                    $user->blockReason .= '公司標記為黑名單';
                    $user->save();
                }

                # 如果是經銷會員可能直接在這邊發送經銷會員驗證信件（只要符合domain or email規則）
                $user = $user->refresh();
                if ($user->isDealer) {
                    if ($user->companyEmailIsDealer && (!$user->company->validated)) {
                        Notification::route('mail', $user->company->email)
                            ->notify(new UserCompanyEmailVerificationNotification($user));
                    }
                } else {
                    $userCompany = $user->company;
                    $userCompany->lastValidate = null;
                    $userCompany->validated = 0;
                    $userCompany->save();
                }

                $subscription = $user->newsletterSubscription;
                if ($subscription && $subscription->newsletterCategories->count()) {
                    $nsfs = app(NewsletterSubscriptionFrontService::class);
                    $nsfs->store(
                        collect([
                            'subscribeNewsletter' => 1,
                            'user' => $user->refresh(),
                        ])
                    );
                }
            }
        }
    }


    public function failed(UpdateCompanyUsersUserGroupAndEdmEvent $event, $exception)
    {
    }
}
