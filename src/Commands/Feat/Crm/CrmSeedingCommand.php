<?php

namespace DaydreamLab\User\Commands\Feat\Crm;

use DaydreamLab\Cms\Models\Category\Category;
use DaydreamLab\Cms\Services\IotCategory\Admin\IotCategoryAdminService;
use DaydreamLab\Cms\Services\Site\Admin\SiteAdminService;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetGroup;
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
            $api = Api::where('method', $apiData['method'])->first();
            if (!$api) {
                Api::create($apiData);
            }
        }

        # 新增"未分類"標籤分類
        $userTagRoot = Category::where('title', 'ROOT')->where('extension', 'usertag')->first();
        if (!$userTagRoot) {
            $userTagRoot = Category::create([
                'title' => 'ROOT',
                'alias' => 'usertag',
                'state' => 1,
                'extension' => 'usertag',
                'access' => 1,
                'language' => '*',
                'ordering' => 1
            ]);
        }

        $uncategory = Category::where('title', '未分類')
            ->where('extension', 'userTag')
            ->first();
        if (!$uncategory) {
            $uncategory = $userTagRoot->children()->create([
                'title' => '未分類',
                'alias' => 'uncategory',
                'state' => 1,
                'extension' => 'usertag',
                'access' => 1,
                'language' => '*',
                'ordering' => 2
            ]);
        }

        $this->info('處理公司銷售錄 api 中...');

        $assetGroup = AssetGroup::where('title', 'COM_MEMBERS_TITLE')->first();
        $asset = Asset::where('title', 'COM_MEMBERS_TAX_MANAGER_TITLE')->first();
        $apis = Api::whereIn('name', ['搜尋公司銷售記錄', '匯入公司銷售記錄'])->get();
        $userGroups = UserGroup::whereIn('title', ['Super User', 'Administrator', '網站管理員'])->get();
        foreach ($apis as $api) {
            $asset->apis()->syncWithoutDetaching([
                $api->id => [
                    'asset_group_id' => $assetGroup->id
                ]
            ]);
            foreach ($userGroups as $userGroup) {
                $api->userGroups()->syncWithoutDetaching([
                    $userGroup->id => [
                        'asset_group_id' => $assetGroup->id,
                        'asset_id' => $asset->id
                    ]
                ]);
            }
        }
        $this->info('處理公司銷售錄 api 完成');
    }
}
