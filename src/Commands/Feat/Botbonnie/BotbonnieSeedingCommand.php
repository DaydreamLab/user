<?php

namespace DaydreamLab\User\Commands\Feat\Botbonnie;

use DaydreamLab\Cms\Models\Category\Category;
use DaydreamLab\Cms\Services\IotCategory\Admin\IotCategoryAdminService;
use DaydreamLab\Cms\Services\Site\Admin\SiteAdminService;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use DaydreamLab\User\Services\Asset\Admin\AssetGroupAdminService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BotbonnieSeedingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:botbonnie-seed';

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
        $data = getJson(__DIR__ . '/jsons/tags.json', true);
        foreach ($data as $tag) {
            $client = new Client();
            $response = $client->get(
                'https://api.botbonnie.com/v1/api/tag/' . 'tf-Uw-m6ZXQd',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . config('app.botbonnie_token'),
                        'Content-Type' => 'application/json'
                    ]
                ]
            );
            $tagData = json_decode($response->getBody()->getContents());
            show($tagData);
            exit();
        }
    }
}
