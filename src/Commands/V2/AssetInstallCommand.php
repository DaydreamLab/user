<?php

namespace DaydreamLab\User\Commands\V2;

use DaydreamLab\User\Models\Asset\AssetGroup;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Services\Api\Admin\ApiAdminService;
use DaydreamLab\User\Services\Asset\Admin\AssetAdminService;
use Illuminate\Console\Command;

class AssetInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:v2-asset-install';

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
        $this->info('更新 V2 asset 資料...');
        $this->installAsset();
        $this->info('更新 V2 asset 資料完成');
    }


    public function installAsset()
    {
        $assetGroup = AssetGroup::where('title', 'COM_MEMBERS_TITLE')->first();

        $assetData = [
            'title' => 'COM_MEMBERS_PEOPLE_MANAGER_TITLE',
            "path" => "people",
                "full_path" => "/people",
                "component" => "members/people/list",
                "type" => "menu",
                "state" => 1,
                "icon" => "copy",
                "showNav" => 1,
                "apis" => [
                ],
                "defaultApis" => [
                    "getOption" => [
                        "checked",
                        "hidden"
                    ]
                ]
        ];

        $asset = app(AssetAdminService::class)->store(collect($assetData));
        $assetGroup->assets()->attach($asset->id);

        $apiData = [
            'name'  => '搜尋公司成員',
            'state' => 1,
            'method' => 'searchCompanyMember',
            'url' => '/admin/company/{id}/user/search',
        ];

        $api = app(ApiAdminService::class)->store(collect($apiData));
        $asset->apis()->attach($api->id, ['asset_group_id' => $assetGroup->id]);

        $userGroups = UserGroup::whereIn('id', [4,5,8,9])->get();
        foreach ($userGroups as $userGroup) {
            $userGroup->assets()->attach($asset->id);
            $userGroups->apis()->attach($api->id, ['asset_group_id' => $assetGroup->id, 'asset_id' => $asset->id]);
        }
    }
}
