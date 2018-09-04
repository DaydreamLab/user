<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserRoleMapStorePost extends AdminRequest
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
            'user_id'       => 'required|integer',
            'role_ids'      => 'required|array',
            'role_ids.*'    => 'required|integer',
            'redirect'      => 'required|string',
        ];
    }
}
