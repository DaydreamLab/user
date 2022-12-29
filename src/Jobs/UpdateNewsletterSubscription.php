<?php

namespace DaydreamLab\User\Jobs;

use DaydreamLab\User\Models\UserTag\UserTag;
use DaydreamLab\User\Services\User\Front\UserFrontService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateNewsletterSubscription implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->onQueue('update-newsletter-subscription-job');
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $sub = $this->user->newsletterSubscription;
        app(UserFrontService::class)->handleUserNewsletterSubscription(collect([
                'subscribeNewsletter' => $sub->newsletterCategories->count() ? 1 : 0,
            ]), $this->user);
    }
}
