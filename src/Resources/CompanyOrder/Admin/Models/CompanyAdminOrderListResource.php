<?php

namespace DaydreamLab\User\Resources\CompanyOrder\Admin\Models;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class CompanyAdminOrderListResource extends BaseJsonResource
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
            'brand' => $this->brand->title,
            'date'  => Carbon::parse($this->date)->tz($tz)->format('Y-m')
        ];
    }
}
