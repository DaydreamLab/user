<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\User\Requests\User\UserStorePost;
use Illuminate\Validation\Rule;

class UserAdminStorePost extends UserStorePost
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
            'role_id'   => 'required|string',
            'redirect'  => 'nullable|string',
            'block'     => [
                'required',
                Rule::in(0,1)
            ],
            'reset_password'    => [
                'required',
                Rule::in(0,1)
            ],

        ];
        return array_merge($rules, parent::rules());
    }
}
