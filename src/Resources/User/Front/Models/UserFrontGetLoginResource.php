<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFrontGetLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userGroup = $this->groups->first();

        $data = [
            'uuid'                  => $this->uuid,
            'email'                 => $this->email,
            'backupEmail'           => $this->backupEmail,
            'name'                  => $this->name,
            'mobilePhoneCode'       => $this->mobilePhoneCode,
            'mobilePhoneNumber'     => $this->mobilePhoneNumber,
            'groupId'               => $userGroup
                ? $userGroup->id
                : null,
            'group'                 => $userGroup
                ? $userGroup->title
                : ''
        ];

        if ($this->company) {
            $data['company'] = [
                'name'          => $this->company->name,
                'email'         => $this->company->email,
                'vat'           => $this->company->vat,
                'phoneCode'     => $this->company->phoneCode,
                'phone'         => $this->company->phone,
                'extNumber'     => $this->company->extNumber,
                'city'          => $this->company->city,
                'district'      => $this->company->district,
                'address'       => $this->company->address,
                'zipcode'       => $this->company->zipcode,
                'department'    => $this->company->department,
                'jobTitle'      => $this->company->jobTitle,
                'industry'      => $this->company->industry,
                'scale'         => $this->company->scale,
                'purchaseRole'  => $this->company->purchaseRole,
                'interestedIssue'   => $this->company->interestedIssue,
                'issueOther'    => $this->company->issueOther
            ];
        }

        if ($this->accessToken) {
            $data['token'] = $this->accessToken;
        }

        if ($this->lineAccountLinkRedirectUrl) {
            $data['lineAccountLinkRedirectUrl'] = $this->lineAccountLinkRedirectUrl;
        }

        if ($this->isAdmin()) {
            $data['redirect'] = $this->groups->sortBy('id')->last()->redirect;
        }

        return $data;
    }
}
