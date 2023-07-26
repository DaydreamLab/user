<?php

namespace DaydreamLab\User\Resources\User\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Helpers\EnumHelper;
use DaydreamLab\User\Models\User\UserGroup;
use DaydreamLab\User\Resources\User\Admin\Models\UserAdminCrmSearchResource;

class UserAdminCrmSearchResourceCollection extends BaseResourceCollection
{
    public $collects = UserAdminCrmSearchResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $normalGroups = UserGroup::select(['id', 'title'])
            ->whereIn('title', EnumHelper::SITE_USER_GROUPS)
            ->get();
        $request->normalGroups = $normalGroups;
        return parent::toArray($request);
    }
}
