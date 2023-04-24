<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserAdminExportResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userCompany = $this->company;
        $company = $userCompany->company;
        $phones = $userCompany->phones ?: [];
        $phoneStr = '';
        foreach ($phones as $key => $phone) {
            $phoneStr .= $phone['phoneCode'] . '-' . $phone['phone'];
            if ($phone['ext']) {
                $phoneStr .= '#' . $phone['ext'];
            }
            if ($key != count($phones) - 1) {
                $phoneStr .= ',';
            }
        }

        return [
            $this->groups->first()->title,
            ($company) ? $company->name : '',
            ($company) ? $company->vat : '',
            $phoneStr,
            ($userCompany) ? $userCompany->email : '',
            ($company) ? implode(',', $company->industry) : '',
            ($company) ? $company->scale : '',
            $this->mobilePhone ?: '',
            $this->name ?: '',
            $this->email ?: '',
            ($userCompany) ? $userCompany->jobType : '',
            ($userCompany) ? $userCompany->jobCategory : '',
            ($userCompany) ? $userCompany->purchaseRole : '',
            ($userCompany) ? implode(',', $userCompany->interestedIssue) : '',
            $this->block ? '是' : '否',
            $this->line ? '已綁訂' : '未綁訂',
            $this->lastLoginAt ? Carbon::parse($this->lastLoginAt)->tz('Asia/Taipei')->toDateTimeString() : '無',
            $this->validateStatus
        ];
    }
}
