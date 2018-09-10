<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserStorePost extends AdminRequest
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
            'email'                 => 'required|email',
            'password'              => 'nullable|string|min:8|max:16',
            'password_confirmation' => 'nullable|same:password',
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'nickname'              => 'nullable|string',
            'gender'                => 'nullable|string',
            'image'                 => 'nullable|string',
            'birthday'              => 'nullable|date',
            'phone_code'            => 'nullable|string',
            'phone'                 => 'nullable|string',
            'school'                => 'nullable|string',
            'job'                   => 'nullable|string',
            'country'               => 'nullable|string',
            'state'                 => 'nullable|string',
            'city'                  => 'nullable|string',
            'district'              => 'nullable|string',
            'address'               => 'nullable|string',
            'zipcode'               => 'nullable|string',
            'timezone'              => 'nullable|string',
            'language'              => 'nullable|string',
        ];
    }
}
