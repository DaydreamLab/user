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
        $mailDomain = count($this->mailDomains['domain'])
            ? $this->mailDomains['domain']
            : $this->mailDomains['email'];
        $phonesStr = '';
        foreach ($this->phones ?: [] as $key => $phone) {
            if ($key != 0) {
                $phonesStr .= ',';
            }
            $phonesStr .= $phone['phoneCode'] . '-' . $phone['phone'];
        }

        return [
            ($this->category) ? $this->category->title : '',
            $this->categoryNote,
            $this->name,
            $this->vat,
            implode(',', $this->industry),
            $this->userCompanies->count(),
            implode(',', $mailDomain),
            $phonesStr,
            $this->scale ?: '無資料',
            $this->getDateTimeString($this->approvedAt),
            $this->getDateTimeString($this->expiredAt),
        ];
    }
}
