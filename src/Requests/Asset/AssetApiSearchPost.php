<?php

namespace DaydreamLab\User\Requests\Asset;

use DaydreamLab\JJAJ\Requests\ListRequest;
use Illuminate\Validation\Rule;

class AssetApiSearchPost extends ListRequest
{

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
             'method' => 'nullable|string',
//            'state'     => [
//                'nullable',
//                'integer',
//                Rule::in([0,1,-2])
//            ]
        ];

        return array_merge(parent::rules(), $rules);
    }
}
