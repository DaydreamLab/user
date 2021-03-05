<?php

namespace DaydreamLab\User\Commands;

use Illuminate\Console\Command;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install DaydreamLab user component';


    protected $seeder_namespace = 'DaydreamLab\\User\\Database\\Seeders\\';


    protected $seeders = [
        'AssetsTableSeeder',
        'UsersGroupsTableSeeder',
        'UsersTableSeeder',
        'ViewlevelsTableSeeder',
        'UserGroupDefaultAccessGroupsSeeder'
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
        foreach ($this->seeders as $seeder) {
            $this->info('Start seeding :'. $seeder);
            $this->call('db:seed', [
                '--class' => $this->seeder_namespace . $seeder
            ]);
            $this->info('Complete seeding: ' .$seeder);
        }
    }
}
