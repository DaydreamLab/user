<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;
use DaydreamLab\User\Resources\Company\Admin\Models\CompanyAdminResource;

class UserAdminListResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tz = $request->user('api')->timezone;

        return [
            'id'            => $this->id,
            'email'         => $this->email,
            'name'          => $this->name,
            'firstName'     => $this->firstName,
            'lastName'      => $this->lastName,
            'company'       => $this->company ? new CompanyAdminResource($this->company) : null,
            'mobilePhoneCode' => $this->moiblePhoneCode,
            'mobilePhone'   => $this->mobilePhone,
            'block'         => $this->block,
            'activation'    => $this->activation,
            'lastLoginAt'   => $this->getDateTimeString($this->lastLoginAt, $tz),
            'lastLoginIp'   => $this->lastLoginIp,
            'groups'        => $this->groups->pluck('title'),
        ];
    }
}
