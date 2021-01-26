<?php

namespace DaydreamLab\User\Requests\Asset\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class AssetApiAdminStorePost extends AdminRequest
{
    protected $modelName = 'AssetApi';

    protected $apiMethod = 'storeAsset';
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
            'service'       => 'required|string',
            'method'        => 'required|string',
            'url'           => 'required|string',
        ];
        return array_merge($rules, parent::rules());
    }
}
