<?php

namespace DaydreamLab\User\Resources\UserTag\Admin\Models;

use DaydreamLab\Dsth\Resources\Notification\Admin\Collections\NotificationAdminSearchResourceCollection;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagAdminResource extends BaseJsonResource
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
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'alias'     => $this->alias,
            'categoryId' => $this->categoryId,
            'categoryTitle' => $this->category->title,
            'type'      => $this->type,
            'description' => $this->description,
            'rules'     => new UserTagAdminRulesResource($this->rules),
            'notifications' => new NotificationAdminSearchResourceCollection($this->notifications, false)
        ];
    }
}
