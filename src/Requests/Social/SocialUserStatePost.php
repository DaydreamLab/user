<?php

namespace DaydreamLab\User\Requests\Social;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class SocialUserStatePost extends AdminRequest
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
            'ids'       => 'required|array',
            'ids.*'     => 'required|integer',
            'state'     => [
                'required',
                'integer',
                Rule::in([0,1,-2])
            ]
        ];
    }
}
