<?php

namespace DaydreamLab\User\Services\Asset\Admin;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\User\Repositories\Asset\Admin\AssetApiAdminRepository;
use DaydreamLab\User\Services\Asset\AssetApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
            $map = $this->assetApiMapAdminService->findBy('api_id', '=', $input->id)->first();
            $map->delete();
            $this->assetApiMapAdminService->storeKeysMap(Helper::collect([
                'asset_id'  => $input->asset_id,
                'api_ids'   => [
                    $input->id
                ]
            ]));
            return $this->modify($input->toArray());
        }
    }


    public function storeMaps(Collection $input)
    {
        foreach ($input->api_ids as $api_id) {
            $api    = $this->find($api_id);
            $update = $api->update(['asset_id' => $input->asset_id]);
            if (!$update) {
                $this->status = Str::upper(Str::snake($this->type.'StoreMapFail'));
                $this->response = null;
                return false;
            }
        }
        $this->status = Str::upper(Str::snake($this->type.'StoreMapSuccess'));
        $this->response = null;
        return true;
    }

}
