<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\UserTag\Admin\Models\UserTagAdminGetUsersResource;

class UserTagAdminGetUsersResourceCollection extends BaseResourceCollection
{
    public $collects = UserTagAdminGetUsersResource::class;

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
