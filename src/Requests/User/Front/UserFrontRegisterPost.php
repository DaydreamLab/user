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
            'password'              => 'required|string|min:8|max:16',
            'password_confirmation' => 'required|same:password',
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'gender'                => 'nullable|string',
            'image'                 => 'nullable|string',
            'phone'                 => 'nullable|string',
            'birthday'              => 'nullable|date',
            'country'               => 'nullable|string',
            'state'                 => 'nullable|string',
            'city'                  => 'nullable|string',
            'district'              => 'nullable|string',
            'address'               => 'nullable|string',
            'zipcode'               => 'nullable|string'
        ];
    }
}
