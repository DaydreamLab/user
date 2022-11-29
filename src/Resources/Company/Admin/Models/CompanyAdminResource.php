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
        $tz = $request->user('api')->timezone;

        return [
            'id'                => $this->id,
            'category_id'       => $this->category_id,
            'name'              => $this->name,
            'vat'               => $this->vat,
//            'domain'            => $this->domain,
            'mailDomains'       => $this->mailDomains,
            'logo'              => $this->logo,
//            'country'           => $this->country,
//            'state'             => $this->state_,
            'city'              => $this->city,
            'district'          => $this->district,
            'address'           => $this->address,
            'phones'            => $this->phones,
            'industry'          => $this->industry,
            'scale'             => $this->scale,
            'categoryNote'      => $this->categoryNote,
            'reason'            => $this->reason,
//            'zipcode'           => $this->zipcode,
//            'introtext'         => $this->introtext,
//            'description'       => $this->description,
//            'created_at'        => $this->getDateTimeString($this->created_at, $tz),
//            'updated_at'        => $this->getDaㄑˋteTimeString($this->updated_at, $tz),
//            'locked_at'         => $this->getDateTimeString($this->locked_at, $tz),
            'approvedAt'        => $this->getDateTimeString($this->approvedAt, $tz),
            'expiredAt'         => $this->getDateTimeString($this->expiredAt, $tz),
//            'creatorName'       => $this->creatorName,
//            'updaterName'       => $this->updaterName,
//            'lockerName'        => $this->lockerName,
//            'locker'            => ($this->locker) ? $this->locker->only(['id', 'uuid', 'name']) : []
        ];
    }
}
