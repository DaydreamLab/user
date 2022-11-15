<?php

namespace DaydreamLab\User\Resources\User\Front\Models;

use DaydreamLab\JJAJ\Traits\FormatDateTime;
use DaydreamLab\User\Helpers\EnumHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFrontResource extends JsonResource
{
    use FormatDateTime;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        # 看事前台還是後台登入給對應的群組
        $groups = $this->groups;
        $group = $request->get('code')
            ? $groups->reject(function ($g) {
                return in_array($g->id, [6, 7]);
            })->values()->first()
            : $groups->reject(function ($g) {
                return !in_array($g->id, [6, 7]);
            })->values()->first();

        $data = [
            'uuid'          => $this->uuid,
            'mobilePhoneCode' => $this->mobilePhoneCode,
            'mobilePhone'   => $this->mobilePhone,
            'email'         => $this->email,
            'backupEmail'   => $this->backupEmail,
            'name'          => $this->name,
            'group'         => $group ? $group->title : '',
            'groupId'       => $group ? $group->id : null,
            'newsletterSubscriptions' => $this->newsletterSubscriptions,
            'subscribeNewsletter'   => count($this->newsletterSubscriptions) ? 1 : 0,
            'canApplyDealer'    => !$this->company || ($this->company && !$this->company->company) || (
                $this->company
                && $this->company->company
                && $this->company->company->category->title == '一般'
                && $this->company->company->status == EnumHelper::COMPANY_NEW
            ),
            'dealerExpired' => $this->isDealer && ($this->company && $this->company->isExpired),
            'dealerValidate' => $this->isDealer && ($this->company && $this->company->validated),
            'lastValidate'  => $this->getDateTimeString(
                $this->company && $this->company->lastValidate
                    ? $this->company->lastValidate
                    : null
            )
        ];

        $userCompany = $this->company;
        $company = $this->company ? $this->company->company : null;
        $data['company'] = [
            'name'          => $company ? $company->name : null,
            'vat'           => $company ? $company->vat : null,
            'email'         => $userCompany ? $userCompany->email : null,
            'phones'        => $userCompany ? $userCompany->phones : [],
//            'phoneCode'     => $userCompany ? $userCompany->phoneCode : null,
//            'phone'         => $userCompany ? $userCompany->phone : null,
//            'extNumber'     => $userCompany ? $userCompany->extNumber : null,
            'city'          => $company ? $company->city : null,
            'district'      => $company ? $company->district : null,
            'address'       => $company ? $company->address : null,
            'zipcode'       => $company ? $company->zipcode : null,
            'department'    => $userCompany ? $userCompany->department : null,
            'jobType'       => $userCompany ? $userCompany->jobType : null,
            'jobCategory'   => $userCompany ? $userCompany->jobCategory : null,
            'jobTitle'      => $userCompany ? $userCompany->jobTitle : null,
            'industry'      => $userCompany ? $userCompany->industry : null,
            'scale'         => $company ? $company->scale : null,
            'purchaseRole'  => $userCompany ? $userCompany->purchaseRole : null,
            'interestedIssue'   => $userCompany ? $userCompany->interestedIssue : [],
            'issueOther'    => $userCompany ? $userCompany->issueOther : null
        ];

        return $data;
    }
}
