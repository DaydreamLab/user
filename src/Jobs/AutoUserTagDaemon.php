<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\UserTag\UserTag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoUserTagDaemon implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('auto-tag');
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $autoTags = UserTag::where('type', 'auto')->where('state', 1)->get();
        foreach ($autoTags as $autoTag) {
            dispatch(new AutoUserTagUpdate($autoTag));
        }
    }
}
