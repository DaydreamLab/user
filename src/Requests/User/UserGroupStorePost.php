<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserGroupStorePost extends AdminRequest
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
            'parent_id'     => 'nullable|integer',
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'redirect'      => 'nullable|string',
            'canDelete'     => [
                'nullable',
                Rule::in([0,1])
            ],
            'ordering'      => 'nullable|integer',
        ];
    }
}
