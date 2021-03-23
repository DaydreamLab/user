<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontRegisterPost extends AdminRequest
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
            'email'                 => 'required|email|unique:users,email',
            'email_confirmation'    => 'required|same:email',
            'password'              => 'required|string|min:8|max:16',
            'password_confirmation' => 'required|same:password',
            'user_name'             => 'required|string',
            'gender'                => 'required|string',
            'identity'              => 'required|string',
            'phone'                 => 'required|string',
            'how'                   => 'required|string',

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
