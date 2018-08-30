<?php

namespace DaydreamLab\User\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\AssetsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\AssetsApisTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\AssetsApisMapsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\AssetsGroupsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\AssetsGroupsMapsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\RolesTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\RolesAssetsMapsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\RolesApisMapsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\UsersRolesMapsTableSeeder'
        ]);

        $this->call('db:seed', [
            '--class' => 'DaydreamLab\\User\\Database\\Seeds\\UsersTableSeeder'
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'user-configs'
        ]);
    }
}
