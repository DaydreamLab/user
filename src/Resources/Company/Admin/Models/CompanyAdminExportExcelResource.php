<?php

namespace DaydreamLab\User\Resources\Company\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;
use DaydreamLab\User\Resources\Company\Admin\Collections\CompanyAdminExportResourceCollection;

class CompanyAdminExportExcelResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $headers = [
            '身份類別',
            '公司註記',
            '公司名稱',
            '公司統編',
            '公司產業別',
            '會員人數',
            '網域',
            '公司電話',
            '公司規模',
            '成為經銷商日期',
            '經銷商過期日期',
        ];
        $data = (new CompanyAdminExportResourceCollection($this->resource))->toArray($request);
        $data['items'] = array_merge([$headers], $data['items']->toArray($request));

        return $data;
    }
}
