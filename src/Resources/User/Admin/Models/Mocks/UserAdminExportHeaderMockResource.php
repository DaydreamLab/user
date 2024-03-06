<?php

namespace DaydreamLab\User\Resources\User\Admin\Models\Mocks;

use Carbon\Carbon;
use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class UserAdminExportHeaderMockResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [$this->resource];
    }
}
