<?php

namespace DaydreamLab\User\Listeners;

use DaydreamLab\Cms\Services\NewsletterSubscription\Front\NewsletterSubscriptionFrontService;
use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

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

                $subscription = $user->newsletterSubscription;
                if ($subscription && $subscription->newsletterCategories->count()) {
                    $nsfs = app(NewsletterSubscriptionFrontService::class);
                    $nsfs->store(collect([
                        'subscribeNewsletter'       => 1,
                        'user'                      => $user->refresh(),
                    ]));
                }
            }
        }
    }


    public function failed(UpdateCompanyUsersUserGroupAndEdmEvent $event, $exception)
    {
    }
}
