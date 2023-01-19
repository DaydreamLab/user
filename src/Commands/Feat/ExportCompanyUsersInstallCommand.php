<?php

namespace DaydreamLab\User\Commands\Feat;

use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetGroup;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Console\Command;

class ExportCompanyUsersInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:feat-export-company-user-install';

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
        $api = Api::where('method', 'exportCompanyMembers')->first();
        if (!$api) {
            $api = Api::create([
                'name'  => '匯出公司成員',
                'state' => 1,
                'method' => 'exportCompanyMembers',
                'url' => '/admin/company/{id}/user/export',
            ]);

            $assetGroup = AssetGroup::where('title', 'COM_MEMBERS_TITLE')->first();
            $asset = Asset::where('title', 'COM_MEMBERS_PEOPLE_MANAGER_TITLE')->first();
            $asset->apis()->attach($api->id, ['asset_group_id' => $assetGroup->id]);

            $userGroups = UserGroup::whereIn('id', [4,5,8,9])->get();
            foreach ($userGroups as $userGroup) {
                $userGroup->apis()->attach($api->id, ['asset_group_id' => $assetGroup->id, 'asset_id' => $asset->id]);
            }
        }
    }
}
