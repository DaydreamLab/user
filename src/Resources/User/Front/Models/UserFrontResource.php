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
            'groupId'       => $this->groups->first() ? $this->groups->first()->id : null,
            'newsletterSubscriptions' => $this->newsletterSubscriptions,
            'subscribeNewsletter'   => count($this->newsletterSubscriptions) ? 1 : 0
        ];

        $data['company'] = [
            'name'          => ($this->company) ? $this->company->name: null,
            'vat'           => ($this->company) ? $this->company->vat: null,
            'email'         => ($this->company) ? $this->company->email: null,
            'phoneCode'     => ($this->company) ? $this->company->phoneCode: null,
            'phone'         => ($this->company) ? $this->company->phone: null,
            'extNumber'     => ($this->company) ? $this->company->extNumber: null,
            'city'          => ($this->company) ? $this->company->city: null,
            'district'      => ($this->company) ? $this->company->district: null,
            'address'       => ($this->company) ? $this->company->address: null,
            'zipcode'       => ($this->company) ? $this->company->zipcode: null,
            'department'    => ($this->company) ? $this->company->department: null,
            'jobTitle'      => ($this->company) ? $this->company->jobTitle: null,
            'industry'      => ($this->company) ? $this->company->industry: null,
            'scale'         => ($this->company) ? $this->company->scale: null,
            'purchaseRole'  => ($this->company) ? $this->company->purchaseRole: null,
            'interestedIssue'   => ($this->company) ? $this->company->interestedIssue: [],
            'issueOther'    => ($this->company) ? $this->company->issueOther: null
        ];

        return $data;
    }
}
