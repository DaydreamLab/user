<?php

namespace DaydreamLab\User\Requests;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class AssetApiStoreMapsPost extends AdminRequest
{
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
            'asset_id'  => 'required|integer',
            'api_ids'   => 'required|array',
            'api_ids.*' => 'nullable|integer'
        ];
        return array_merge($rules, parent::rules());
    }
}
