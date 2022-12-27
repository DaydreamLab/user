<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagAdminResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $timezone = $request->user('api')->timezone;

        return [
        ];
    }
}
