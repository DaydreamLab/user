<?php

namespace DaydreamLab\User\Helpers;

use Illuminate\Support\Str;
use Laravel\Socialite\Two\User;

class UserGroupHelper
{
    public static function getPageInfo($assetGroups, $apis)
    {
        $data = [];
        foreach ($assetGroups as $assetGroup) {
            $tempAssetGroup = $assetGroup->only(['id', 'title']);
            $assets = $assetGroup->assets;
            foreach ($assets as $asset) {
                $assetApis = $asset->apis->map(function ($assetApi) use ($apis, $assetGroup, $asset) {
                    $targetApi = $apis->filter(function ($api) use ($assetGroup, $asset, $assetApi) {
                        return $api->pivot->asset_group_id == $assetGroup->id
                            && $api->pivot->asset_id == $asset->id
                            && $api->pivot->api_id == $assetApi->id;
                    })->first();
                    return [
                        'id'        => $assetApi->id,
                        'name'      => $assetApi->name,
                        'hidden'    => $assetApi->pivot->hidden,
                        'disabled'  => $assetApi->pivot->disabled,
                        'checked'   => $targetApi ? 1 : $assetApi->pivot->checked,
                    ];
                })->values()->all();
                $tempAsset = $asset->only(['id', 'title']);
                $tempAsset['apis'] = $assetApis;
                $tempAssetGroup['assets'][] = $tempAsset;
            }
            $data[] = $tempAssetGroup;
        }

        return $data;
    }
}
