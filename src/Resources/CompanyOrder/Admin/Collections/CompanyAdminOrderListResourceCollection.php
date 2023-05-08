<?php

namespace DaydreamLab\User\Resources\CompanyOrder\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\CompanyOrder\Admin\Models\CompanyAdminOrderListResource;

class CompanyAdminOrderListResourceCollection extends BaseResourceCollection
{
    public $collects = CompanyAdminOrderListResource::class;

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
