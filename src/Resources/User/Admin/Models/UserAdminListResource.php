<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;
use DaydreamLab\User\Models\User\UserGroup;

use function GuzzleHttp\normalize_header_keys;

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

        $export = $request->get('export');
        if (!$export) {
            $tz = $request->user('api')->timezone;
            $normalGroups = $request->normalGroups;

            return [
                'id'            => $this->id,
                'email'         => $request->get('parent_group') == 4 ? $this->email : $this->company->email,
                'name'          => $this->name,
                'firstName'     => $this->firstName,
                'lastName'      => $this->lastName,
                'company'       => $this->company ? $this->company->name : '',
                'companyCategoryTitle' => $this->company->company ? $this->company->company->category->title : '一般',
                'mobilePhoneCode' => $this->moiblePhoneCode,
                'mobilePhone'   => $this->mobilePhone,
                'backupMobilePhone'   => $this->backupMobilePhone,
                'block'         => $this->block,
                'activation'    => $this->activation,
                'lastLoginAt'   => $this->getDateTimeString($this->lastLoginAt, $tz),
                'lastLoginIp'   => $this->lastLoginIp,
                'groups'        => ($request->get('pageGroupId') == 16)
                    ? // 排除掉管理員以外的群組
                        $this->groups->filter(function ($g) use ($normalGroups) {
                            return !in_array($g->id, $normalGroups->pluck('id')->all());
                        })->sortByDesc('id')->pluck('title')->take(1)
                    : $this->groups->sortByDesc('id')->pluck('title')->all(),
            ];
        } else {
            $groupTitle = $this->groups->first()->title;
            return [
                $groupTitle,
                ($this->company) ? $this->company->name : '',
                ($this->company) ? $this->company->vat : '',
                ($this->company) ? $this->company->phoneCode : '',
                ($this->company) ? $this->company->phone : '',
                ($this->company) ? $this->company->extNumber : '',
                ($this->company) ? $this->company->email : '',
                ($this->company) ? $this->company->industry : '',
                ($this->company) ? $this->company->scale : '',
                ($this->mobilePhone && $groupTitle != '無手機名單') ? $this->mobilePhone : '',
                $this->name ?: '',
                $this->email ?: '',
                ($this->company) ? $this->company->department : '',
                ($this->company) ? $this->company->jobTitle : '',
                ($this->company) ? $this->company->purchaseRole : '',
                ($this->company) ? implode(',', $this->company->interestedIssue) : '',
                $this->block,
                $this->blockReason
            ];
        }
    }
}
