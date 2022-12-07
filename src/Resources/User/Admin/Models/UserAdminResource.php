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
            $group_ids = $this->groups->pluck('id')->reject(function ($value) {
                return in_array($value, [6,7]);
            })->values();
        } else {
            $group_ids = $this->groups->pluck('id')->reject(function ($value) {
                return !in_array($value, [6,7]);
            })->values();
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
            'lastResetAt'           => $this->getDateTimeString($this->lastResetAt, $timezone),
            'lastLoginAt'           => $this->getDateTimeString($this->lastLoginAt, $timezone),
            'lastLoginIp'           => $this->lastLoginIp,
            'createdAt'             => $this->getDateTimeString($this->created_at, $timezone),
            'updatedAt'             => $this->getDateTimeString($this->updatedAt, $timezone),
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
            'subscribeNewsletter'   => $this->newsletterSubscription->newsletterCategories->count() ? 1 : 0,
            'cancelAt'   => $this->getDateTimeString($this->newsletterSubscription->cancelAt),
            'cancelReason'   => $this->newsletterSubscription->cancelReason,
        ];
    }
}
