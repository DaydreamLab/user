<?php

namespace DaydreamLab\User\Resources\Company\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class CompanyAdminSearchUsersResource extends BaseJsonResource
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
        $groups = $this->groups->whereIn('title', ['一般會員', '經銷會員', '外部會員', '無手機名單']);

        return [
            'id' => $this->id,
            'mobilePhone' => $this->mobilePhone,
            'email' => $this->company->email,
            'name' => $this->name,
            'groupTitle' => $groups->count()
                ? $groups->first()->title
                : null,
            'jobCategory' => $this->company->jobCategory,
            'jobType' => $this->company->jobType,
            'jobTitle' => $this->company->jobTitle,
            'lastLoginAt' => $this->getDateTimeString($this->lastLoginAt, $tz),
            'subscriptionStatus' => $this->subscriptionStatus,
            'updateStatus' => $this->updateStatus,
            'validateStatus' => $this->validateStatus
        ];
    }
}
