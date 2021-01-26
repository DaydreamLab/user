<?php

namespace DaydreamLab\User\Resources\Asset\Admin\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetApiAdminResource extends JsonResource
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
            'id'                => $this->id,
            'service'           => $this->service,
            'method'            => $this->method,
            'url'               => $this->url,
        ];
    }
}
