<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;
use DaydreamLab\User\Models\User\UserGroup;

use function GuzzleHttp\normalize_header_keys;

class UserAdminCrmSearchResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'email'         => $this->email,
            'name'          => $this->name,
            'company'       => $this->company ? $this->company->name : '',
            'mobilePhoneCode' => $this->moiblePhoneCode,
            'mobilePhone'   => $this->mobilePhone,
            'groupTitle'        => $this->groups->filter(function ($g) use ($request) {
                return in_array($g->id, $request->normalGroups->pluck('id')->all());
            })->sortByDesc('id')->pluck('title')->first(),
            'companyNote' => (!$this->company || !$this->company->company)
                ? ''
                : $this->company->company->categoryNote,
            'tags' => $this->userTags->map(function ($tag) {
                return $tag->only(['id', 'title', 'type']);
            })
        ];
    }
}
