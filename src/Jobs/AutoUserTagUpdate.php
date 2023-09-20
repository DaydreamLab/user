<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\Dsth\Helpers\NotificationHelper;
use DaydreamLab\User\Models\User\User;
use DaydreamLab\User\Models\UserTag\UserTag;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AutoUserTagUpdate implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;

    protected $tag;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserTag $tag)
    {
        $this->tag = $tag;
        $this->onQueue('auto-tag');
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $oldUsers = $this->tag->activeUsers;
        $forceDeleteUserIds = $this->tag->users->where('pivot.forceDelete', 1)->pluck('id')->values();
        $forceAddUserIds = $this->tag->users->where('pivot.forceAdd', 1)->pluck('id')->values();

        $oldIds = $oldUsers->pluck('id');
        $newUserIds = app(UserTagAdminService::class)->getCrmUserIds(collect([
            'rules' => $this->tag->rules
        ]))->reject(function ($id) use ($forceDeleteUserIds) {
            return in_array($id, $forceDeleteUserIds->all());
        })->merge($forceAddUserIds)->unique()->values();


        $intersectIds = $oldIds->intersect($newUserIds);
        $removeIds = $oldIds->diff($intersectIds);
        $addIds = $newUserIds->diff($intersectIds);

        $notifications = $this->tag->notifications->where('isAuto', 1)->where('state', 1);
        foreach ($notifications as $notification) {
            $behavior = $notification->params['behavior'] ?? null;
            $targetIds = [];
            if ($behavior === 'join') {
                $targetIds = $addIds;
            } elseif ($behavior === 'leave') {
                $targetIds = $removeIds;
            }

            if (empty($targetIds) || ($targetIds instanceof Collection && $targetIds->count() == 0)) {
                continue;
            }

            $targetUsers = User::whereIn('id', $targetIds)->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->company->email,
                    'mobilePhone'   => $user->mobilePhone
                ];
            });

            NotificationHelper::createAndNotifyRecords($notification, $targetUsers);
        }

        # 強制被加入名單的不會被移除
        foreach ($removeIds as $removeId) {
            $targetUser = $oldUsers->where('id', $removeId)->first();
            if ($targetUser->pivot->forceAdd) {
                $newUserIds[$removeId] = ['forceAdd' => 1];
            }
        }

        $this->tag->users()->sync($newUserIds);
    }
}
