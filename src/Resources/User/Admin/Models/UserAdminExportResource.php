<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserAdminExportResource extends BaseJsonResource
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
            $this->groups->first()->title,
            ($this->company) ? $this->company->name : '',
            ($this->company) ? $this->company->vat : '',
            ($this->company) ? $this->company->phone : '',
            ($this->company) ? $this->company->extNumber : '',
            ($this->company) ? $this->company->email : '',
            ($this->company) ? $this->company->industry : '',
            ($this->company) ? $this->company->scale : '',
            $this->mobilePhone ?: '',
            $this->name ?: '',
            $this->email ?: '',
            ($this->company) ? $this->company->department : '',
            ($this->company) ? $this->company->jobTitle : '',
            ($this->company) ? $this->company->purchaseRole : '',
            ($this->company) ? implode(',', $this->company->interestedIssue) : '',
            $this->block,
            $this->blockReason
        ];
    }
}
