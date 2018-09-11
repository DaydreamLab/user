<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\Admin\AssetApiAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;
use Illuminate\Support\Collection;

class AssetApiAdminService extends AssetApiService
{
    protected $type = 'AssetApiAdmin';

    protected $assetApiMapAdminService;

    public function __construct(AssetApiAdminRepository $repo,
                                AssetApiMapAdminService $assetApiMapAdminService)
    {
        $this->assetApiMapAdminService = $assetApiMapAdminService;
        parent::__construct($repo);
    }


    public function store(Collection $input)
    {
        if ($input->get('id') == null || $input->get('id') == '') {
            $api = $this->add($input->toArray());
            $this->assetApiMapAdminService->storeKeysMap(Helper::collect([
                'asset_id'  => $input->asset_id,
                'api_ids'   => [
                    $api->id
                ]
            ]));

            return $api;
        }
        else {
            $this->assetApiMapAdminService->storeKeysMap(new Collection([
                'asset_id'  => $input->asset_id,
                'api_ids'   => [
                    $input->id
                ]
            ]));
            return $this->modify($input->toArray());
        }
    }
}
