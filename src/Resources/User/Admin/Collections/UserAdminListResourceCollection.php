<?php

namespace DaydreamLab\User\Resources\User\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\User\Admin\Models\UserAdminListResource;

class UserAdminListResourceCollection extends BaseResourceCollection
{
    public $collects = UserAdminListResource::class;

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
