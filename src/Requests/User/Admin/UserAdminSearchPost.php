<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\ListRequest;
use Illuminate\Validation\Rule;

class UserAdminSearchPost extends ListRequest
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
            'email'     => 'nullable|string',
            'block'     => [
                'nullable',
                'integer',
                Rule::in([0,1,-2])
            ],
            'activation'    => [
                'nullable',
                'integer',
                Rule::in([0,1])
            ],
            'search'    => 'nullable|string',
            'groups'    => 'nullable|integer'
        ];
        return array_merge($rules, parent::rules());
    }
}
