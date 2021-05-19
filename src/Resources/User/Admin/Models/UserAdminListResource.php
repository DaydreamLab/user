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
            'firstName'            => $this->firstName,
            'lastName'             => $this->lastName,
            'block'                 => $this->block,
            'activation'            => $this->activation,
            'groups'                => $this->groups->pluck('title'),
        ];
    }
}
