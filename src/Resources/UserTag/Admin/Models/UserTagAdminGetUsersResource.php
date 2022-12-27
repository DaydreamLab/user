<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagAdminGetUsersResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userGroup =  $this->groups->filter(function ($group) {
            return in_array($group->id, [6,7]);
        })->first();

        return [
            'id' => $this->id,
            'name'  => $this->name,
            'companyName' => $this->company->company ? $this->company->company->name : '',
            'userGroupTitle' => $userGroup ? $userGroup->title : '',
            'mobilePhone'   => $this->mobilePhone,
            'email'     => $this->company->email,
            'tags'      => $this->userTags->map(function ($userTag) {
                return $userTag->only('id', 'title', 'type');
            })
        ];
    }
}
