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
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'user_name'             => $this->user_name,
            'block'                 => $this->block,
            'activation'            => $this->activation,
            'groups'                => $this->groups->toArray(),
        ];
    }
}
