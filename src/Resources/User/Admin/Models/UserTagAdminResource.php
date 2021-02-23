<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Helpers\Helper;
use DaydreamLab\JJAJ\Traits\FormatDateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTagAdminResource extends JsonResource
{
    use FormatDateTime;
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
            'id'    => $this->id,
            'alias' => $this->alias,
            'creator'   => $this->creator,
            'updater'   => $this->updater,
            'created_at' => $this->getDateTimeString($this->created_at, $timezone),
            'updated_at' => $this->getDateTimeString($this->updated_at, $timezone)
        ];
    }
}
