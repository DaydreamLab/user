<?php

namespace DaydreamLab\User\Requests\User;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\User\Rules\DaydreamAccount;

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
            'first_name'            => 'required|string',
            'last_name'             => 'nullable|string',
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
            'company_name'          => 'nullable|string',
            'tax_id_number'         => 'nullable|string',
            'company_tel_locale'    => 'nullable|string',
            'company_tel_number'    => 'nullable|string',
            'company_tel_extension' => 'nullable|string',
            'mobile_phone'          => 'nullable|string',
            'department'            => 'nullable|string',
            'job_title'             => 'nullable|string',
            'become_zerone_member'  => 'nullable|boolean',
            'zerone_subscriptions'  => 'nullable|boolean',
            'zerone_breaking_news'  => 'nullable|boolean',
            'timezone'              => 'nullable|string',
            'locale'                => 'nullable|string',
        ];
    }
}
