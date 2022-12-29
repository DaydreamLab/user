<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Jobs\UpdateNewsletterSubscription;
use DaydreamLab\User\Models\User\User;
use Illuminate\Console\Command;

class HandleNewsletterSubscriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:newsletter-subscription-handle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install DaydreamLab user component';

    protected $constants = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('處理公司信箱與個人信箱訂閱更換...');
        $this->handleNewsletterSubscription();
        $this->info('處理公司信箱與個人信箱訂閱更換完成');
    }


    public function handleNewsletterSubscription()
    {
        # 確保每個會員都有對應的電子報訂閱
        $users = User::all();
        foreach ($users as $user) {
            dispatch(new UpdateNewsletterSubscription($user));
        }
    }
}
