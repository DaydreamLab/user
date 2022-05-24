<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFrontLoginResource extends JsonResource
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
            'name'          => $this->name,
            'group'         => $this->groups->first() ? $this->groups->first()->title : '',
            'email'         => $this->email,
            'backupEmail'   => $this->backupEmail,
            'company'       => [
                'name'          => $this->company->name,
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
            ]
        ];

//        if ($this->accessToken) {
//            $data['token'] = $this->token;
//        }

        if ($this->isAdmin()) {
            $data['redirect'] = $this->groups->sortBy('id')->last()->redirect;
        }

        return $data;
    }
}
