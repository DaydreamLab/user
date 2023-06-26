<?php

namespace DaydreamLab\User\Resources\User\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Models\User\UserGroup;
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
        $normalGroups = UserGroup::select(['id', 'title'])
            ->whereIn('title', ['一般會員', '經銷會員', '外部會員', '無手機名單'])
            ->get();
        $request->normalGroups = $normalGroups;
        return parent::toArray($request);
    }
}
