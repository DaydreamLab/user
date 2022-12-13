<?php

namespace DaydreamLab\User\Resources\NotificationTemplate\Collections;

use DaydreamLab\JJAJ\Resources\BaseResourceCollection;
use DaydreamLab\User\Resources\NotificationTemplate\Models\NotificationTemplateListResource;

class NotificationTemplateListResourceCollection extends BaseResourceCollection
{
    public $collects = NotificationTemplateListResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
