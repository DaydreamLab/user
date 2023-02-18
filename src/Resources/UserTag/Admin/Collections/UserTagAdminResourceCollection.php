<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\UserTag\Admin\Models\UserTagAdminResource;
use DaydreamLab\User\Resources\UserTag\Admin\Models\UserTagAdminSearchResource;

class UserTagAdminResourceCollection extends BaseResourceCollection
{
    public $collects = UserTagAdminResource::class;

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
