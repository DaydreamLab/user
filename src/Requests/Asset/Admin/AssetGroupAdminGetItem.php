<?php

namespace DaydreamLab\User\Requests\Asset\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class AssetGroupAdminGetItem extends AdminRequest
{
    protected $modelName = 'AssetGroup';

    protected $apiMethod = 'getItem';
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

        ];
        return array_merge(parent::rules(), $rules);
    }
}
