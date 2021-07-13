<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontVerifyCodeRequest extends AdminRequest
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
            'mobilePhoneCode'  => 'required|regex:/\+[0-9]+$/',
            'mobilePhone'      => 'required|numeric',
            'verificationCode' => 'required|numeric'
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
