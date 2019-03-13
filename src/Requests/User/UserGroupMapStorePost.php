<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserGroupMapStorePost extends AdminRequest
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
            'group_ids'     => 'required|array',
            'group_ids.*'   => 'required|integer',
        ];
    }
}
