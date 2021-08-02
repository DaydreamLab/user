<?php

namespace DaydreamLab\User\Resources\Asset\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class AssetGroupAdminResource extends BaseJsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'state'         => $this->state,
            'description'   => $this->description,
            'params'        => $this->params,
            'assetIds'      => $this->assets->pluck('id'),
            'createdAt'     => $this->getDateTimeString($this->created_at, $timezone),
            'updatedAt'     => $this->getDateTimeString($this->updated_at, $timezone),
        ];
    }
}
