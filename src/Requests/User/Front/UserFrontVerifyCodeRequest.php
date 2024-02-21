<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class UserFrontVerifyCodeRequest extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'verifyVerificationCode';

    protected $needAuth = false;

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


    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        return $validated;
    }
}
