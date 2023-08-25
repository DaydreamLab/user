<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagAdminSearchResource extends BaseJsonResource
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

        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'type'          => $this->type,
            'categoryId' => $this->categoryId,
            'categoryTitle' => $this->category->title,
            'description' => $this->description,
            'activeUsers'   => $this->activeUsers->count(),
            'createdAt'     => $this->getDateTimeString($this->created_at, $tz),
            'creatorName'   => $this->creator->name,
            'rules'     => new UserTagAdminRulesResource($this->rules),
        ];
    }
}
