<?php

namespace DaydreamLab\User\Resources\Asset\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\Asset\Admin\Models\AssetApiAdminResource;

class AssetApiAdminListResourceCollection extends BaseResourceCollection
{
    public $collects = AssetApiAdminResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
