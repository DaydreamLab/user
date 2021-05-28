<?php

namespace DaydreamLab\User\Requests\Asset\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class AssetAdminOrderingPost extends AdminRequest
{
    protected $modelName = 'Asset';

    protected $apiMethod = 'orderingAsset';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'id'            => 'required|integer',
            'ordering'      => 'nullable|integer',
            'parentId'      => 'nullable|integer',
            'parent_id'     => 'nullable|integer',
        ];
        return array_merge(parent::rules(), $rules);
    }
}
