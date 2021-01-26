<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
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
            'firstName'             => $this->first_name,
            'lastName'              => $this->last_name,
            'gender'                => $this->gendor,
            'image'                 => $this->image,
            'phone'                 => $this->phone,
            'birthday'              => $this->birthday,
            'country'               => $this->country,
            'state'                 => $this->state,
            'city'                  => $this->city,
            'district'              => $this->district,
            'address'               => $this->address,
            'zipcode'               => $this->zipcode,
            'activation'            => $this->activation,
            'block'                 => $this->block,
            'groups'                => $this->groups->map(function ($group) {
                return $group->title;
            }),
            'access_ids'            => $this->access_ids
        ];
    }
}