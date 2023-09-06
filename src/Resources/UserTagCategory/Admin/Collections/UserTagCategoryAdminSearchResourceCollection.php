<?php

namespace DaydreamLab\User\Resources\UserTagCategory\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\UserTagCategory\Admin\Models\UserTagCategoryAdminSearchResource;

class UserTagCategoryAdminSearchResourceCollection extends BaseResourceCollection
{
    public $collects = UserTagCategoryAdminSearchResource::class;

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
