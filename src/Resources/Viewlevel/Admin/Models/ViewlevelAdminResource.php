<?php

namespace DaydreamLab\User\Resources\Viewlevel\Admin\Models;

use DaydreamLab\User\Models\User\UserGroup;
use Illuminate\Http\Resources\Json\JsonResource;

class ViewlevelAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userGroups = UserGroup::whereIn('id', $this->rules ?: [])->get();
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'rules'         => $userGroups->count()
                ? $userGroups->map(function ($group) {
                    return $group->only(['id', 'title']);
                })->all()
                : []
        ];
    }
}
