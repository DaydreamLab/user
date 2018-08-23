<?php

namespace DaydreamLab\User\Requests\Asset;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class AssetStorePost extends AdminRequest
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
            'id'        => 'nullable|integer',
            'parent_id' => 'nullable|integer',
            'name'      => 'nullable|string',
            'path'      => 'nullable|string',
            'component' => 'nullable|string',
            'type'      => 'required|string',
            'icon'      => 'required|string',
            'state'     => [
                'required',
                'integer',
                Rule::in([0,1,-2])
            ],
            'redirect'  => 'nullable|string',
            'showNav'   => 'integer|between:0,1',
        ];
    }
}
