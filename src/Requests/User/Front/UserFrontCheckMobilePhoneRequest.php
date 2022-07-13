<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\Dsth\Helpers\EnumHelper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use Illuminate\Validation\Rule;

class UserFrontCheckMobilePhoneRequest extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'checkMobilePhone';

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
            'mobilePhoneCode'   => 'required|regex:/\+[0-9]+$/',
            'mobilePhone'       => 'required|numeric',
            'email'             => 'nullable|email'
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
