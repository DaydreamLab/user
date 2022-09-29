<?php

namespace DaydreamLab\User\Requests\Company\Front;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\User\Helpers\CompanyRequestHelper;

class CompanyFrontApplyPost extends AdminRequest
{

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
            'name' => 'required|string',
            'city'  => 'required|string',
            'district'  => 'required|string',
            'address'   => 'required|string',
            'industry'  => 'required|string',
            'scale' => 'required|string',
            'phones'    => 'required|array',
            'phones.*'    => 'required|array',
            'phones.*.phoneCode'    => 'required|numeric',
            'phones.*.phone'    => 'required|numeric',
            'phones.*.ext'    => 'required|numeric',
//            'salesInfo' => 'nullable|array',
//            'salesInfo.*' => 'nullable|array',
//            'salesInfo.*.name' => 'required|string',
//            'salesInfo.*.mobilePhone' => 'required|string',
//            'salesInfo.*.email' => 'required|email',
            'mailDomains' => 'required|array',
            'mailDomains.domain' => 'nullable|array',
            'mailDomains.domain.*' => 'required|string',
            'mailDomains.email' => 'nullable|array',
            'mailDomains.email.*' => 'required|email',
        ];
    }

    public function validated()
    {
        $validated = parent::validated();
        $validated->put('user', $this->user());
        $validated->put('phones', CompanyRequestHelper::handlePhones($validated->get('phones')));
//        $validated->put('salesInfo', $this->handleSalesInfo($validated->get('salesInfo')));
        $validated->put('mailsDomains', CompanyRequestHelper::handleMailDomains($validated->get('mailDomains')));

        return $validated;
    }
}
