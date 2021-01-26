<?php

namespace DaydreamLab\User\Requests\Asset\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class AssetGroupAdminStorePost extends AdminRequest
{
    protected $modelName = 'AssetGroup';

    protected $apiMethod = 'storeAssetGroup';
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
            'id'            => 'nullable|integer',
            'title'         => 'required|string',
            'state'         => [
                'required',
                'integer',
                Rule::in([0,1,-2])
            ],
            'asset_ids'     => 'required|array',
            'asset_ids.*'   => 'nullable|integer',
        ];
        return array_merge($rules, parent::rules());
    }
}
