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
        return [
            'name'          => $this->name,
            'firstName'     => $this->firstName,
            'lastName'      => $this->lastName,
            'redirect'      => '/',
            'token'         => $this->token,
        ];
    }
}
