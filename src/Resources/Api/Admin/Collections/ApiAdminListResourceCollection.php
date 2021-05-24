<?php

namespace DaydreamLab\User\Resources\Api\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\Api\Admin\Models\ApiAdminResource;

class ApiAdminListResourceCollection extends BaseResourceCollection
{
    public $collects = ApiAdminResource::class;

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
