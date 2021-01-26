<?php

namespace DaydreamLab\User\Resources\Viewlevel\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\Viewlevel\Admin\Models\ViewlevelAdminResource;

class ViewlevelAdminListResourceCollection extends BaseResourceCollection
{
    public $collects = ViewlevelAdminResource::class;

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
