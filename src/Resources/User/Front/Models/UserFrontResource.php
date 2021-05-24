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
        return [
            'email'                 => $this->email,
            'name'                  => $this->name,
            'firstName'             => $this->firstName,
            'lastName'              => $this->lastName,
            'gender'                => $this->gendor,
            'image'                 => $this->image,
            'phoneCode'             => $this->phoneCode,
            'phone'                 => $this->phone,
            'mobilePhone'           => $this->mobilePhone,
            'birthday'              => $this->birthday,
            'country'               => $this->country,
            'state'                 => $this->state_,
            'city'                  => $this->city,
            'district'              => $this->district,
            'address'               => $this->address,
            'zipcode'               => $this->zipcode,
            'resetPassword'         => $this->resetPassword
        ];
    }
}
