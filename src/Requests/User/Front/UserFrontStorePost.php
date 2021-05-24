<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontStorePost extends AdminRequest
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
            'firstName'             => 'required|string',
            'lastName'              => 'required|string',
            'nickname'              => 'nullable|string',
            'password'              => 'nullable|string|min:8|max:16',
            'passwordConfirm'       => 'required_with:password|nullable|same:password',
            'gender'                => 'nullable|string',
            'image'                 => 'nullable|string',
            'birthday'              => 'nullable|date_format:Y-m-d',
            'phoneCode'             => 'nullable|string',
            'phone'                 => 'nullable|string',
            'mobilePhone'           => 'nullable|string',
            'country'               => 'nullable|string',
            'state'                 => 'nullable|string',
            'city'                  => 'nullable|string',
            'district'              => 'nullable|string',
            'address'               => 'nullable|string',
            'zipcode'               => 'nullable|string',
            'timezone'              => 'nullable|string',
            'locale'                => 'nullable|string',
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        if ($password = $validated->get('password')) {
            $validated->put('password', bcrypt($password));
        }

        return $validated;
    }
}
