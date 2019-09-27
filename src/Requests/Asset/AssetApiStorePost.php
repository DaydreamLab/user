<?php

namespace DaydreamLab\User\Requests\Asset;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class AssetApiStorePost extends AdminRequest
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
        return [
            'id'            => 'nullable|integer',
            'service'       => 'required|string',
            'method'        => 'required|string',
            'url'           => 'required|string',
        ];
    }
}
