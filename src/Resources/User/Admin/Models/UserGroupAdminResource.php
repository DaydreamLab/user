<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $page = $this->handlePage($this->assetGroups, $this->apis);

        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'parent_id'     => $this->parent_id,
            'tree_title'    => $this->tree_title,
            'description'   => $this->tree_title,
            'canDelete'     => $this->canDelete,
            'ordering'      => $this->ordering,
            'redirect'      => $this->redirect,
            'page'          => $page
        ];
    }


    public function handlePage($assetGroups, $apis)
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
