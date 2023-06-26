<?php

namespace DaydreamLab\User\Commands\Feat\OuterEvent;

use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetGroup;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Services\User\Admin\UserGroupAdminService;
use Illuminate\Console\Command;

class OuterEventInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:outer-event-install';

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
        $outerGroup = UserGroup::where('title', '外部會員')->first();
        if (!$outerGroup) {
            $normalGroup = UserGroup::where('title', '一般會員')->first();
            app(UserGroupAdminService::class)->store(
                collect([
                    'parent_id' => $normalGroup->id,
                    'title' => '外部會員',
                    'canDelete' => 0,
                    'description' => '外部會員',
                    'access'    => 8,
                ])
            );
        }

        $nonePhoneGroup = UserGroup::where('title', '無手機名單')->first();
        if (!$nonePhoneGroup) {
            $normalGroup = UserGroup::where('title', '一般會員')->first();
            app(UserGroupAdminService::class)->store(
                collect([
                    'parent_id' => $normalGroup->id,
                    'title' => '無手機名單',
                    'canDelete' => 0,
                    'description' => '無手機名單',
                    'access'    => 8,
                ])
            );
        }

        # 匯入無手機名單api
        $assetGroup = AssetGroup::where('title', 'COM_MEMBERS_TITLE')->first();
        $asset = Asset::where('title', 'COM_MEMBERS_MANAGER_TITLE')->first();
        $api = Api::where('name', '匯入無手機名單')->first();
        if (!$api) {
            $api = Api::create([
                'name' => '匯入無手機名單',
                'method' => 'importNonePhone',
                'url' => '/admin/user/importNonePhone',
                'created_by' => 1
            ]);
            $asset->apis()->attach($api->id, ['asset_group_id' => $assetGroup->id]);
            $api->userGroups()->attach([4,5,8,9], [
                'asset_group_id' => $assetGroup->id,
                'asset_id' => $asset->id
            ]);
        }
    }
}
