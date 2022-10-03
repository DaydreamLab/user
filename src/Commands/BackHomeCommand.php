<?php

namespace DaydreamLab\User\Commands;

use DaydreamLab\User\Jobs\BackHomeNotify;
use DaydreamLab\User\Models\User\User;
use Illuminate\Console\Command;

class BackHomeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:back-home';

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
        $hour = now()->tz('Asia/Taipei')->format('H') ;
        if (in_array($hour, ['08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18'])) {
            $users = User::whereNull('backHomeSendAt')->limit(11)->get();
            foreach ($users as $i => $user) {
                $user->backHomeSendAt = now()->toDateTimeString();
                $user->save();
                BackHomeNotify::dispatch($user)->delay(now()->addSeconds($i));
            }
        }
    }
}
