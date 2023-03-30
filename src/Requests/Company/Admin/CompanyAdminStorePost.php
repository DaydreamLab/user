<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\Domain;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;
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
            'categoryId'    => 'required|integer',
            'name'          => 'required|string',
            'vat'           => ['nullable', new TaiwanUnifiedBusinessNumber()],
            'logo'          => 'nullable|string',
//            'country'       => 'nullable|string',
//            'state'         => 'nullable|string',
            'city'          => 'nullable|string',
            'district'      => 'nullable|string',
            'address'       => 'nullable|string',
//            'zipcode'       => 'nullable|string',
//            'introtext'     => 'nullable|string',
//            'description'   => 'nullable|string',
            'categoryNote' => ['nullable', Rule::in([
                EnumHelper::COMPANY_NOTE_NONE,
                EnumHelper::COMPANY_NOTE_COMPETITION,
                EnumHelper::COMPANY_NOTE_STAFF,
                EnumHelper::COMPANY_NOTE_BLACKLIST,
                EnumHelper::COMPANY_NOTE_OBM
            ])],
            'reason'       => 'nullable|string',
            'industry'      => 'nullable|array',
            'industry.*'    => 'required|string',
            'scale'         => 'nullable|string',
            'mailDomains' => 'nullable|array',
            'mailDomains.domain' => 'nullable|array',
            'mailDomains.domain.*' => ['required', new Domain()],
            'mailDomains.email' => 'nullable|array',
            'mailDomains.email.*' => 'required|email',
            'phones'    => 'nullable|array',
            'phones.*'    => 'required|array',
            'phones.*.phoneCode'    => 'required|numeric',
            'phones.*.phone'    => 'required|numeric',
            'phones.*.ext'    => 'nullable|numeric',
            'approvedAt'    => 'nullable|date_format:Y-m-d H:i:s',
            'expiredAt'     => 'nullable|date_format:Y-m-d H:i:s',
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
