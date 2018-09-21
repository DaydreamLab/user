<?php

namespace DaydreamLab\User\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

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


    protected $seeder_namespace = 'DaydreamLab\\User\\Database\\Seeds\\';

    protected $constants = [
        'user',
        'asset',
        'role',
        'input',
        'viewlevel'
    ];

    protected $seeders = [
        'AssetsTableSeeder',
        'RolesTableSeeder',
        'UsersGroupsTableSeeder',
        'UsersTableSeeder',
        'ViewlevelsTableSeeder',
    ];

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
        //$this->call('jjaj:refresh');

        foreach ($this->seeders as $seeder) {
            $this->call('db:seed', [
                '--class' => $this->seeder_namespace . $seeder
            ]);
        }

        $this->deleteConstants();

        $this->call('vendor:publish', [
            '--tag' => 'user-configs'
        ]);
    }


    public function deleteConstants()
    {
        $constants_path     = 'config/constants/';
        foreach ($this->constants as $constant) {
            File::delete($constants_path . $constant . '.php');
        }
    }
}
