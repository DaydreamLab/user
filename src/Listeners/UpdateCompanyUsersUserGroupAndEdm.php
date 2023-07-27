<?php

namespace DaydreamLab\User\Listeners;

use DaydreamLab\Cms\Services\NewsletterSubscription\Front\NewsletterSubscriptionFrontService;
use DaydreamLab\User\Events\UpdateCompanyUsersUserGroupAndEdmEvent;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\User\UserGroup;
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
        $userGroups = UserGroup::whereIn('title', ['經銷會員', '一般會員', '外部會員', '無手機名單'])->get();
        $outerGroup = $userGroups->where('title', '外部會員')->first();
        $nonePhoneGroup = $userGroups->where('title', '無手機名單')->first();
        $company = $event->company;
        $companyUserGroup = $event->companyUserGroup;
        foreach ($company->userCompanies as $userCompany) {
            if ($user = $userCompany->user) {
                # 這邊要考慮管理員同時擁有經銷商資格
                $original = $user->groups->pluck('id');
                $adminGroupIds = $original->reject(function ($o) use ($userGroups) {
                    return in_array($o, $userGroups->pluck('id')->all());
                })->values()->all();

                # 外部會員不會因為公司級別改變而改變會員群組
                if ($original->contains($outerGroup->id)) {
                    $adminGroupIds[] = $outerGroup->id;
                } elseif ($original->contains($nonePhoneGroup->id)) {
                    $adminGroupIds[] = $nonePhoneGroup->id;
                } else {
                    $adminGroupIds[] = $companyUserGroup->id;
                }
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
                    $userCompany->name = $company->name;
                } else {
                    $userCompany = $user->company;
                    $userCompany->lastValidate = null;
                    $userCompany->name = $company->name;
                    $userCompany->validated = 0;
                }
                $userCompany->save();

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
