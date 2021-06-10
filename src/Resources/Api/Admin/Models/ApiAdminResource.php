<?php

namespace DaydreamLab\User\Resources\Api\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class ApiAdminResource extends BaseJsonResource
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
            'name'          => $this->name,
            'state'         => $this->state,
            'method'        => $this->method,
            'url'           => $this->url,
            'description'   => $this->description,
            'params'        => $this->params,
            'createdAt'     => $this->getDateTimeString($this->created_at, $tz),
            'updateAt'      => $this->getDateTimeString($this->updated_at, $tz),
        ];
    }
}
