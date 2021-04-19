<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontEditPost extends AdminRequest
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
            'id'                    => 'nullable|integer',
            //'email'                 => 'nullable|email|unique:users,email',
            //'email_confirmation'    => 'required_with:email|same:email',
            'password'              => 'nullable|string|min:8|max:16',
            'password_confirmation' => 'required_with:password|same:password',
            'user_name'             => 'nullable|string',
            'gender'                => 'nullable|string',
            'identity'              => 'nullable|string',
            'phone'                 => 'nullable|string',
            'how'                   => 'nullable|array',

            'unit'                  => 'nullable|string',
            'unit_department'       => 'nullable|string',
            'job_title'             => 'nullable|string',
            'school'                => 'nullable|string',
            'school_department'     => 'nullable|string',
            'grade'                 => 'nullable|string',
            'subscription'          => 'nullable|boolean',
        ];
    }
}
