<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BackHomeJobPart2 implements ShouldQueue
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
        $this->onQueue('back-home-job');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = now();
        for ($i = 0; $i < 12500; $i++) {
            $user = User::where('mobilePhone', 'regexp', '[0-9]+')
                ->whereNotNull('email')
                ->offset($i + 12500)
                ->limit(1)
                ->first();
            BackHomeNotify::dispatch($user)->delay($now->addSeconds($i * 3));
        }
    }
}
