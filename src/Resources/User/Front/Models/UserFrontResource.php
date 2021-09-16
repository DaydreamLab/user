<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFrontResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'uuid'          => $this->uuid,
            'mobilePhoneCode' => $this->mobilePhoneCode,
            'mobilePhone'   => $this->mobilePhone,
            'email'         => $this->email,
            'backupEmail'   => $this->backupEmail,
            'name'          => $this->name,
            'group'         => $this->groups->first() ? $this->groups->first()->title : '',
            'newsletterSubscriptions' => $this->newsletterSubscriptions
        ];

        if ($this->company) {
            $data['company'] = [
                'name'          => $this->company->name,
                'vat'           => $this->company->vat,
                'email'         => $this->company->email,
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

        return $data;
    }
}
