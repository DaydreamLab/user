<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontRegisterMobilePhonePost extends AdminRequest
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
            'uuid'          => 'required|uuid',
            'name'          => 'required|string',
            'email'         => 'required|email',
            'company'       => 'required|array',
            'company.name'  => 'required|string',

            'phone'         => 'nullable|string',
            'birthday'      => 'nullable|date',
            'country'       => 'nullable|string',
            'state'         => 'nullable|string',
            'city'          => 'nullable|string',
            'district'      => 'nullable|string',
            'address'       => 'nullable|string',
            'zipcode'       => 'nullable|string'
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
