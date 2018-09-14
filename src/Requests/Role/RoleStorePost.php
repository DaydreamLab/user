<?php

namespace DaydreamLab\User\Requests\Role;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class RoleStorePost extends AdminRequest
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
            'redirect'      => 'required|string',
            'state'         => [
                'required',
                'integer',
                Rule::in([0,1,-2])
            ],
            'canDelete'     => [
                'required',
                'integer',
                Rule::in([0,1])
            ],
            'order'         => 'nullable|integer',
            'description'   => 'nullable|string'
        ];
    }
}
