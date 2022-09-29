<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\User\Helpers\CompanyRequestHelper;
use DaydreamLab\User\Helpers\EnumHelper;
use Illuminate\Validation\Rule;

class CompanyAdminStorePost extends AdminRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'storeCompany';
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
        $rules = [
            'id'            => 'nullable|integer',
            'status'        => ['required', Rule::in([
                EnumHelper::COMPANY_NONE,
                EnumHelper::COMPANY_NEW,
                EnumHelper::COMPANY_PENDING,
                EnumHelper::COMPANY_APPROVED,
                EnumHelper::COMPANY_REJECTED
            ])],
            'categoryId'    => 'nullable|integer',
            'name'          => 'required|string',
            'vat'           => 'required|string',
            'logo'          => 'nullable|string',
//            'country'       => 'nullable|string',
//            'state'         => 'nullable|string',
            'city'          => 'nullable|string',
            'district'      => 'nullable|string',
            'address'       => 'nullable|string',
//            'zipcode'       => 'nullable|string',
//            'introtext'     => 'nullable|string',
//            'description'   => 'nullable|string',
            'mailDomains' => 'required|array',
            'mailDomains.domain' => 'nullable|array',
            'mailDomains.domain.*' => 'required|string',
            'mailDomains.email' => 'nullable|array',
            'mailDomains.email.*' => 'required|email',
            'phones'    => 'required|array',
            'phones.*'    => 'required|array',
            'phones.*.phoneCode'    => 'required|numeric',
            'phones.*.phone'    => 'required|numeric',
            'phones.*.ext'    => 'required|numeric',
            'ordering'      => 'nullable|integer'
        ];
        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        $validated->put('mailDomains', CompanyRequestHelper::handleMailDomains($validated->get('mailDomains')));
        $validated->put('phones', CompanyRequestHelper::handlePhones($validated->get('phones')));
        $validated->put('state_', $validated->get('state'));
        $validated->put('category_id', $validated->get('categoryId'));
        $validated->forget(['state', 'categoryId']);

        return $validated;
    }
}
