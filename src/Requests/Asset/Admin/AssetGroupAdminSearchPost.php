<?php

namespace DaydreamLab\User\Requests\Asset\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;

class AssetGroupAdminSearchPost extends ListRequest
{
    protected $modelName = 'AssetGroup';

    protected $apiMethod = 'searchAssetGroup';

    protected $searchKeys = ['title', 'description'];
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
            //
        ];
        return array_merge(parent::rules(), $rules);
    }
}
