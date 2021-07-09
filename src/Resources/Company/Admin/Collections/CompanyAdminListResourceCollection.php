<?php

namespace DaydreamLab\User\Resources\Company\Admin\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\Company\Admin\Models\CompanyAdminListResource;

class CompanyAdminListResourceCollection extends BaseResourceCollection
{
    public $collects = CompanyAdminListResource::class;

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
