<?php

namespace DaydreamLab\User\Requests\Role;

use DaydreamLab\JJAJ\Requests\AdminRequest;

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
            'id'                  => 'nullable|integer',
            'parent_id'           => 'required|integer',
            'title'               => 'string',
            'enabled'             => 'required|integer|between:0,1',
            'redirect'            => 'string'
        ];
    }
}
