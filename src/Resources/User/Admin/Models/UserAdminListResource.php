<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminListResource extends JsonResource
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
            'id'                    => $this->id,
            'email'                 => $this->email,
            'firstName'             => $this->first_name,
            'lastName'              => $this->last_name,
            'block'                 => $this->block,
            'activation'            => $this->activation,
            'groups'                => $this->groups->map(function ($group) {
                return $group->title;
            }),
        ];
    }
}
