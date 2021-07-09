<?php

namespace DaydreamLab\User\Resources\Company\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class CompanyAdminListResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $timezone = $request->user('api')->timezone;

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'vat'               => $this->vat,
            'categoryTitle'     => $this->category->title,
            'updatedAt'         => $this->getDateTimeString($this->updatedAt, $timezone),
            'updater'           => $this->updaterName,
        ];
    }
}
