<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use DaydreamLab\User\Models\User\UserGroup;
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
            // 排除掉管理員以外的群組
            $dealerUserGroup = UserGroup::where('title', '經銷會員')->first();
            $userGroup = UserGroup::where('title', '一般會員')->first();
            $data['redirect'] = $this->groups->filter(function ($g) use ($dealerUserGroup, $userGroup) {
                return $g != $dealerUserGroup->id && $g != $userGroup->id;
            })->sortBy('id')->last()->redirect;
        }

        return  $data;
    }
}
