<?php

namespace DaydreamLab\User\Resources\Company\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class CompanyAdminResource extends BaseJsonResource
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
            'category_id'       => $this->category_id,
            'name'              => $this->name,
            'vat'               => $this->vat,
            'domain'            => $this->domain,
            'logo'              => $this->logo,
            'country'           => $this->country,
            'state'             => $this->state_,
            'city'              => $this->city,
            'district'          => $this->district,
            'address'           => $this->address,
            'zipcode'           => $this->zipcode,
            'introtext'         => $this->introtext,
            'description'       => $this->description,
            'createdAt'         => $this->getDateTimeString($this->created_at, $timezone),
            'updatedAt'         => $this->getDateTimeString($this->updatedAt, $timezone),
            'creator'           => $this->creatorName,
            'updater'           => $this->updaterName,
        ];
    }
}
