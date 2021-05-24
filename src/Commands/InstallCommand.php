<?php

namespace DaydreamLab\User\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:install';

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
        $this->call('jjaj:refresh');

        $this->call('user:seed');


        $this->call('vendor:publish', [
            '--tag' => 'user-configs'
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'jjaj-configs'
        ]);
    }
}
