<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\UserTag\UserTag;
use DaydreamLab\User\Services\UserTag\Admin\UserTagAdminService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $this->onQueue('auto-tag-job');
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $oldUsers = $this->tag->activeUsers;
        $oldIds = $oldUsers->pluck('id');
        $newUserIds = app(UserTagAdminService::class)->getCrmUserIds(collect([
            'rules' => $this->tag->rules
        ]));

        $removeIds = $oldIds->diff($newUserIds);
        $addIds = $newUserIds->diff($oldIds);

        $notifications = $this->tag->notifications->where('isAuto', 1)->where('state', 1);
        foreach ($notifications as $notification) {
            # todo: 寄送對應的通知 可能分成 新增、移除 名單時做不同的通知
            foreach ($addIds as $addId) {
            }
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
