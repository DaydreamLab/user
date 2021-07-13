<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;

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
            'uuid'              => 'required|uuid',
            'name'              => 'required|string',
            'email'             => 'required|email',
            'backupEmail'       => 'nullable|email',
            'company'           => 'required|array',
            'company.name'      => 'required|string',
            'company.email'      => 'required|email',
            'company.vat'       => ['required', 'numeric', new TaiwanUnifiedBusinessNumber()],
            'company.phoneCode' => 'required|numeric',
            'company.phone'     => 'required|numeric',
            'company.extNumber' => 'nullable|numeric',
            'company.department'=> 'required|string',
            'company.jobTitle'  => 'required|string',
            'company.city'      => 'required|string',
            'company.district'  => 'required|string',
            'company.address'   => 'required|string',
            'company.zipcode'   => 'nullable|numeric',
            #todo: 電子報
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
