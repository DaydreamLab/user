<?php

namespace DaydreamLab\User\Commands\Hotfix\TotpPermission;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Models\Api\Api;
use DaydreamLab\User\Models\Asset\Asset;
use DaydreamLab\User\Models\Asset\AssetGroup;
use DaydreamLab\User\Models\Company\Company;
use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTotpPermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-totp-permission';

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
        $api = Api::where('method', 'sendTotp')->first();
        if (!$api) {
            $api = Api::create([
                'name' => '寄送TOTP驗證碼',
                'state' => 1,
                'method' => 'sendTotp',
                'url' => '/admin/api/user/sendTotp',
            ]);
        }
        $assetGroup = AssetGroup::where('title', 'COM_PERMISSIONS_TITLE')->first();
        $asset = Asset::where('title', 'COM_PERMISSIONS_ACCOUNT_MANAGER_TITLE')->first();
        $record = DB::table('assets_apis_maps')
            ->where('asset_group_id', $assetGroup->id)
            ->where('asset_id', $asset->id)
            ->where('api_id', $api->id)
            ->first();
        if (!$record) {
            DB::table('assets_apis_maps')
                ->insert([
                    'asset_group_id' => $assetGroup->id,
                    'asset_id' => $asset->id,
                    'api_id' => $api->id,
                    'disabled' =>  0,
                    'hidden' => 1,
                    'checked' => 1
                ]);
        }
    }
}
