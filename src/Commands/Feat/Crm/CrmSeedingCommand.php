<?php

namespace DaydreamLab\User\Commands\Feat\Crm;

use DaydreamLab\Cms\Services\IotCategory\Admin\IotCategoryAdminService;
use DaydreamLab\Cms\Services\Site\Admin\SiteAdminService;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupAdminService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CrmSeedingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:crm-seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'seeding solution data';


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
        $this->apiSeeder();
    }



    public function apiSeeder()
    {
        $data = getJson(__DIR__ . '/jsons/api.json', true);
        $counter = Api::all()->count();
        foreach ($data as $apiData) {
            $apiData['ordering'] = ++$counter;
            Api::create($apiData);
        }
    }
}
