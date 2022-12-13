<?php

namespace DaydreamLab\User\Resources\NotificationTemplate\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class NotificationTemplateResource extends BaseJsonResource
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
            'id'                => $this->id,
            'type'              => $this->type,
            'subject'           => $this->subject,
            'content'           => $this->content,
            'contentHtml'       => $this->contentHtml,
            'expiredAt'         => $this->getDateTimeString($this->expiredAt, $tz),
        ];
    }
}
