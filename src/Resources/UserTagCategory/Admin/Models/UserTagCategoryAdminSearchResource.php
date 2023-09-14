<?php

namespace DaydreamLab\User\Resources\UserTagCategory\Admin\Models;

use DaydreamLab\Dsth\Resources\Notification\Admin\Collections\NotificationAdminSearchResourceCollection;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagCategoryAdminSearchResource extends BaseJsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'tree_title' => $this->tree_title,
            'depth'     => $this->depth,
            'description' => $this->description,
            'tagCount' => $this->userTags->count(),
            'tags'  => $this->userTags->map(function ($tag) {
                return $tag->only('id', 'title');
            }),
            'createdAt' => $this->getDateTimeString($this->created_at, $tz),
            'creatorName' => $this->creatorName,
        ];
    }
}
