<?php

namespace DaydreamLab\User\Requests\Role;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class RoleApiMapStorePost extends AdminRequest
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
            'role_id'       => 'required|integer',
            'api_ids'       => 'required|array',
            'api_ids.*'     => 'nullable|integer'
        ];
    }
}
