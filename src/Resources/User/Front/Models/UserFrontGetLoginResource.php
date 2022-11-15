<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use DaydreamLab\User\Models\User\UserGroup;
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
        $groups = $this->groups;
        $group = $request->get('code')
            ? $groups->reject(function ($g) {return in_array($g->id, [6, 7]);})->values()->first()
            : $groups->reject(function ($g) {return !in_array($g->id, [6, 7]);})->values()->first();

        $data = [
            'uuid'                  => $this->uuid,
            'email'                 => $this->email,
            'backupEmail'           => $this->backupEmail,
            'name'                  => $this->name,
            'mobilePhoneCode'       => $this->mobilePhoneCode,
            'mobilePhoneNumber'     => $this->mobilePhone,
            'groupId'               => $group
                ? $group->id
                : null,
            'group'                 => $group
                ? $group->title
                : ''
        ];

        if ($this->company) {
            $data['company'] = [
                'name'          => $this->company->name,
                'email'         => $this->company->email,
                'vat'           => $this->company->vat,
                'phones'        => $this->company->phones,
//                'phoneCode'     => $this->company->phoneCode,
//                'phone'         => $this->company->phone,
//                'extNumber'     => $this->company->extNumber,
                'city'          => $this->company->city,
                'district'      => $this->company->district,
                'address'       => $this->company->address,
                'zipcode'       => $this->company->zipcode,
                'department'    => $this->company->department,
                'jobCategory'   => $this->company->jobCategory,
                'jobType'       => $this->company->jobType,
                'jobTitle'      => $this->company->jobTitle,
                'industry'      => $this->company->industry,
                'scale'         => $this->company->scale,
                'purchaseRole'  => $this->company->purchaseRole,
                'interestedIssue'   => $this->company->interestedIssue,
                'issueOther'    => $this->company->issueOther
            ];
        }

        if ($this->accessToken) {
            $data['token'] = $this->accessToken;
        }

        if ($this->lineAccountLinkRedirectUrl) {
            $data['lineAccountLinkRedirectUrl'] = $this->lineAccountLinkRedirectUrl;
        }

        if ($this->isAdmin()) {
            // 排除掉管理員以外的群組
            $dealerUserGroup = UserGroup::where('title', '經銷會員')->first();
            $userGroup = UserGroup::where('title', '一般會員')->first();
            $data['redirect'] = $this->groups->filter(function ($g) use ($dealerUserGroup, $userGroup) {
                return $g->id != $dealerUserGroup->id && $g->id != $userGroup->id;
            })->sortBy('id')->last()->redirect;
            $assetGroups = collect([]);
            $this->groups->each(function ($g) use (&$assetGroups) {
                $assetGroups = $assetGroups->merge($g->assetGroups);
            });
            $data['site'] = $assetGroups->pluck(['site_id'])->unique()->values()->toArray();
        }

        return $data;
    }
}
