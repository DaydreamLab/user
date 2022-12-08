<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserCompanyAdminResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'  => $this->name,
            'vat'   => $this->vat,
            'phones'    => $this->phones,
            'email' => $this->email,
            'department' => $this->department,
            'jobCategory' => $this->jobCategory,
            'jobTitle' => $this->jobTitle,
            'jobType' => $this->jobType,
            'industry' => $this->industry,
            'scale' => $this->company ? $this->company->scale : null,
            'purchaseRole' => $this->purchaseRole,
            'interestedIssue' => $this->interestedIssue,
            'issueOther'    => $this->issueOther,
            'validated' => $this->validated,
            'lastValidate'  => $this->getDateTimeString($this->lastValidate),
            'lastUpdate'  => $this->getDateTimeString($this->lastUpdate),
            'isExpired'  => $this->isExpired
        ];
    }
}
