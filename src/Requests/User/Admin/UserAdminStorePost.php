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
            'group_ids'             => 'required|array',
            'group_ids.*'           => 'required|integer',
            'block'                 => [
                'nullable',
                Rule::in(0,1)
            ],
            'reset_password'        => [
                'required',
                Rule::in(0,1)
            ],
            'activation'            => 'nullable|boolean',
            'password'              => 'nullable|string|min:8|max:16',
            'password_confirmation' => 'nullable|same:password',

        ];
        return array_merge($rules, parent::rules());
    }
}
