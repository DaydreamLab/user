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
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'nullable|string|min:8|max:16',
            'password_confirmation' => 'nullable|same:password',
            'first_name'            => 'required|string',
            'last_name'             => 'nullable|string',
            'gender'                => 'nullble|string',
            'image'                 => 'nullble|string',
            'phone'                 => 'nullble|string',
            'birthday'              => 'nullble|date',
            'country'               => 'nullble|string',
            'state'                 => 'nullble|string',
            'city'                  => 'nullble|string',
            'district'              => 'nullble|string',
            'address'               => 'nullble|string',
            'zipcode'               => 'nullble|string',
            'company_name'          => 'required|string',
            'tax_id_number'         => 'nullable|string',
            'company_tel_locale'    => 'nullable|string',
            'company_tel_number'    => 'required|string',
            'company_tel_extension' => 'nullable|string',
            'mobile_phone'          => 'required|string',
            'department'            => 'required|string',
            'job_title'             => 'required|string',
            'become_zerone_member'  => 'nullable|boolean',
            'zerone_subscriptions'  => 'nullable|boolean',
            'zerone_breaking_news'  => 'nullable|boolean'
        ];
    }
}
