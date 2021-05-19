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
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|max:16',
            'passwordConfirm'       => 'required|same:password',
            'firstName'             => 'required|string',
            'lastName'              => 'required|string',
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


    public function validated()
    {
        $validated = parent::validated();
        $validated->put('password', bcrypt($validated->get('password')));
        if ($state = $validated->get('state')) {
            $validated->put('state_', $validated->get('state'));
        }
        $validated->forget(['state', 'passwordConfirm']);

        return $validated;
    }
}
