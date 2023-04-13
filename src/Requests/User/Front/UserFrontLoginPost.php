<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\User\Rules\RecaptchaV3;

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
            'email'     => 'required|email',
            'password'  => 'required|string',
            'code'      => 'nullable|string',
            'g_recaptcha_response' => ['required', new RecaptchaV3()]
        ];
    }
}
