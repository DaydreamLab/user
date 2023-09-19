<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserAdminResource extends BaseJsonResource
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
        if ($request->get('pageGroupId') == 16) {
            $group_ids = $this->groups->filter(function ($g) {
                return !in_array($g->title, ['一般會員', '經銷會員', '外部會員', '無手機名單']);
            })->pluck('id')->values();
        } else {
            $group_ids = $this->groups->filter(function ($g) {
                return in_array($g->title, ['一般會員', '經銷會員', '外部會員', '無手機名單']);
            })->pluck('id')->values();
        }

        return [
            'id'                    => $this->id,
            'email'                 => $this->email,
            'name'                  => $this->name,
            'firstName'             => $this->firstName,
            'lastName'              => $this->lastName,
            'nickname'              => $this->nickname,
            'gender'                => $this->gendor,
            'image'                 => $this->image,
            'phoneCode'             => $this->phoneCode,
            'phone'                 => $this->phone,
            'mobilePhone'           => $this->mobilePhone,
            'birthday'              => $this->birthday,
            'timezone'              => $this->timezone,
            'locale'                => $this->locale,
            'country'               => $this->country,
            'state'                 => $this->state,
            'city'                  => $this->city,
            'district'              => $this->district,
            'address'               => $this->address,
            'zipcode'               => $this->zipcode,
            'activation'            => $this->activation,
            'block'                 => $this->block,
            'lineBind'              => $this->line ? 1 : 0,
            'lastResetAt'           => $this->getDateTimeString($this->lastResetAt, $timezone),
            'lastLoginAt'           => $this->getDateTimeString($this->lastLoginAt, $timezone),
            'lastLoginIp'           => $this->lastLoginIp,
            'createdAt'             => $this->getDateTimeString($this->created_at, $timezone),
            'updatedAt'             => $this->getDateTimeString($this->updated_at, $timezone),
            'createdBy'             => $this->creatorName,
            'updatedBy'             => $this->updaterName,
            'groupIds'              => $group_ids,
            'accessIds'             => $this->accessIds,
            'brandIds'              => $this->brands->pluck('id'),
            'tags'                  => $this->tags->map(function ($tag) {
                return $tag->only(['id', 'title']);
            }),
            'company'               => new UserCompanyAdminResource($this->company),
            'blockReason'           => $this->blockReason,
            'upgradeReason'         => $this->upgradeReason,
            'backupMobilePhone'     => $this->backupMobilePhone,
            'updateStatus'          => $this->updateStatus,
            'validateStatus'        => $this->validateStatus,
            'companyCategoryTitle'  => $this->company
                ? ($this->company->company ? $this->company->company->category->title : '一般')
                : '一般',
            'subscribeNewsletter'   => $this->newsletterSubscription
                ? ($this->newsletterSubscription->newsletterCategories->count() ? 1 : 0)
                : 0,
            'cancelAt'   => $this->newsletterSubscription
                ? $this->getDateTimeString($this->newsletterSubscription->cancelAt)
                : null,
            'cancelReason'   => $this->newsletterSubscription
                ? $this->newsletterSubscription->cancelReason
                : null,
            'userTags'  => $this->userTags->map(function ($tag) {
                return $tag->only('id', 'title');
            })
        ];
    }
}
