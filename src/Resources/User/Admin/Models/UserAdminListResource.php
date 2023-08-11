<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;
use DaydreamLab\User\Models\User\UserGroup;

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
//        $dealerUserGroup = UserGroup::where('title', '經銷會員')->first();
//        $userGroup = UserGroup::where('title', '一般會員')->first();
        $company = $this->company->company;

        return [
            'id'            => $this->id,
            'email'         => $request->get('parent_group') == 4 ? $this->email : $this->company->email,
            'name'          => $this->name,
            'firstName'     => $this->firstName,
            'lastName'      => $this->lastName,
            'company'       => $company ? $this->company->name : '',
            'companyCategoryTitle' => $company ? $company->category->title : '一般',
            'mobilePhoneCode' => $this->moiblePhoneCode,
            'mobilePhone'   => $this->mobilePhone,
            'backupMobilePhone'   => $this->backupMobilePhone,
            'block'         => $this->block,
            'activation'    => $this->activation,
            'lastLoginAt'   => $this->getDateTimeString($this->lastLoginAt, $tz),
            'lastLoginIp'   => $this->lastLoginIp,
            'groups'        => ($request->get('pageGroupId') == 16)
                ? // 排除掉管理員以外的群組
                    $this->groups->filter(function ($g) {
                        return !in_array($g->id, [6,7]);
                    })->sortByDesc('id')->pluck('title')->take(1)
                : $this->groups->sortByDesc('id')->pluck('title')->all(),
        ];
    }
}
