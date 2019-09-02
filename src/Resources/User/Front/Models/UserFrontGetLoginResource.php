<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use DaydreamLab\Dddream\Resources\Lecture\Front\Collections\LecturerFrontResourceCollection;
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
        return [
            'id'            => $this->id,
            'firstName'     => $this->first_name,
            'lastName'      => $this->last_name,
            'redirect'      => '/',
            'token'         => $this->token,
            'groups'        => $this->groups,
        ];
    }
}
