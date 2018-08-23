<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

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
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|max:16',
            'password_confirmation' => 'required|same:password',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'gender'                => 'string',
            'image'                 => 'string',
            'phone'                 => 'string',
            'birthday'              => 'date',
            'country'               => 'string',
            'state'                 => 'string',
            'city'                  => 'string',
            'address'               => 'string',
            'zipcode'               => 'string'
        ];
    }
}
