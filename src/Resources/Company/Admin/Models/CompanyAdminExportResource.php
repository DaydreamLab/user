<?php

namespace DaydreamLab\User\Resources\Company\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;

class CompanyAdminExportResource extends BaseJsonResource
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
            $this->name,
            $this->vat,
            $this->domain,
            ($this->category) ? $this->category->title : '',
            implode(',', $this->industry),
            $this->userCompanies->count(),
            $this->getDateTimeString($this->approvedAt),
            $this->getDateTimeString($this->expiredAt),
        ];
    }
}
