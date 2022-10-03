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
        $tz = $request->user('api')->timezone;

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'status'            => $this->status,
            'vat'               => $this->vat,
            'industry'          => $this->industry,
            'usersCount'        => $this->userCompanies->count(),
            'approvedAt'        => $this->getDateTimeString($this->approvedAt, $tz),
            'expiredAt'         => $this->getDateTimeString($this->expiredAt, $tz),
//            'domain'            => $this->domain,
//            'categoryTitle'     => ($this->category) ? $this->category->title : '',
//            'created_at'        => $this->getDateTimeString($this->created_at, $tz),
//            'updated_at'        => $this->getDateTimeString($this->updated_at, $tz),
//            'locked_at'         => $this->getDateTimeString($this->locked_at, $tz),
//            'creatorName'       => $this->creatorName,
//            'updaterName'       => $this->updaterName,
//            'lockerName'        => $this->lockerName,
//            'locker'            => ($this->locker) ? $this->locker->only(['id', 'uuid', 'name']) : []
        ];
    }
}
