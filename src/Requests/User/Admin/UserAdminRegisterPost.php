<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserAdminRegisterPost extends AdminRequest
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
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|max:16',
            'password_confirmation' => 'required|same:password',
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'gender'                => 'nullble|string',
            'image'                 => 'nullble|string',
            'phone'                 => 'nullble|string',
            'birthday'              => 'nullble|date',
            'country'               => 'nullble|string',
            'state'                 => 'nullble|string',
            'city'                  => 'nullble|string',
            'address'               => 'nullble|string',
            'zipcode'               => 'nullble|string',
            'timezone'              => 'nullable|string',
            'locale'                => 'nullable|string',
        ];
    }
}
