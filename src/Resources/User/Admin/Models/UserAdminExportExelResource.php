<?php

namespace DaydreamLab\User\Resources\User\Admin\Models;

use DaydreamLab\JJAJ\Resources\BaseJsonResource;
use DaydreamLab\User\Resources\User\Admin\Collections\UserAdminExportResourceCollection;

class UserAdminExportExelResource extends BaseJsonResource
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
            '身份',
            '公司註記',
            '公司名稱',
            '公司統編',
            '公司電話',
            '公司信箱',
            '公司產業別',
            '公司規模',
            '行動電話',
            '姓名',
            '個人信箱',
            '職務類別',
            '職位別',
            '採購過程中所扮演的角色',
            '感興趣議題',
            '是否為黑名單',
            '是否有綁定LINE',
            '上次登入日期',
            '是否通過經銷商驗證',
        ];
        $data = (new UserAdminExportResourceCollection($this->resource))->toArray($request);
        $data['items'] = array_merge([$headers], $data['items']->toArray($request));

        return $data;
    }
}
