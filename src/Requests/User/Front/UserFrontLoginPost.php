<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontLoginPost extends AdminRequest
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
            'email'             => 'nullable|email',
            'password'          => 'required_with:email|string',
            'mobilePhoneCode'   => 'nullable|string',
            'mobilePhone'       => 'nullable|string',
            'verificationCode'  => 'required_with:mobilePhone|string',
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        $validated->put('lastLoginIp', $this->getCloudflareIp($this));

        return $validated;
    }
}
