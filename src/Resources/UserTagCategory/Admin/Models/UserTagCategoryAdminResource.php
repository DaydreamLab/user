<?php

namespace DaydreamLab\User\Resources\UserTagCategory\Admin\Models;

use DaydreamLab\Dsth\Resources\Notification\Admin\Collections\NotificationAdminSearchResourceCollection;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserTagCategoryAdminResource extends BaseJsonResource
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
        $autoTags = $this->userTags->where('type', 'auto')->values();
        $manualTags = $this->userTags->where('type', 'manual')->values();
        return [
            'id' => $this->id,
            'parentId' => $this->parent_id,
            'parentTitle' => $this->parent->title,
            'title' => $this->title,
            'description' => $this->description,
            'autoTags' => $autoTags->where('state', 1)->map(function ($tag) use ($tz) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title,
                    'state' => $tag->state,
                    'realTimeUsersCount' => $tag->realTimeActiveUsers($tag->realTimeUsers)->count(),
                    'createdAt' => $this->getDateTimeString($tag->created_at, $tz),
                    'creatorName' => $this->creatorName
                ];
            }),
            'autoTagsCount' => $autoTags->count(),
            'manualTags' => $manualTags->where('state', 1)->map(function ($tag) use ($tz) {
                return [
                    'id' => $tag->id,
                    'title' => $tag->title,
                    'state' => $tag->state,
                    'realTimUsersCount' => $tag->realTimeActiveUsers($tag->realTimeUsers)->count(),
                    'createdAt' => $this->getDateTimeString($tag->created_at, $tz),
                    'creatorName' => $this->creatorName
                ];
            }),
            'manualTagsCount' => $manualTags->count(),
        ];
    }
}
