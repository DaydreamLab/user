<?php

namespace DaydreamLab\User\Requests\User\Front;

use DaydreamLab\Dsth\Helpers\EnumHelper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;
use DaydreamLab\User\Helpers\CompanyRequestHelper;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserFrontRegisterMobilePhonePost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'register';

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
            'uuid'              => 'required|uuid',
            'name'              => 'required|string',
            'email'             => 'required|email',
            'backupEmail'       => 'nullable|email',
            'backupMobilePhone' => 'nullable|numeric',
            'verificationCode'  => 'required|numeric',
            'company'           => 'nullable|array',
            'company.name'      => 'nullable|string',
            'company.email'     => 'nullable|email',
            'company.vat'       => ['nullable', 'numeric', new TaiwanUnifiedBusinessNumber()],
            'company.phones.*.phoneCode' => 'nullable|numeric',
            'company.phones.*.phone' => 'nullable|numeric',
            'company.phones.*.ext'  => 'nullable|numeric',
            'company.department' => 'nullable|string',
            'company.jobTitle'  => 'nullable|string',
            'company.jobCategory'    => 'nullable|string',
            'company.jobType'      => 'nullable|string',
            'company.industry'      => 'nullable|string',
            'company.scale'         => 'nullable|string',
            'company.purchaseRole'  => 'nullable|string',
            'company.interestedIssue'   => 'nullable|array',
            'company.interestedIssue.*' => 'nullable|string',
            'company.issueOther'    => 'nullable|string',
            'newsletterCategoriesAlias'     => 'nullable|array',
            'newsletterCategoriesAlias.*'   => 'nullable|string',
            'subscribeNewsletter'       => ['nullable', Rule::in(EnumHelper::BOOLEAN)]
        ];
    }


    public function validated()
    {
        $validated = parent::validated();

        $validated->put('email', Str::lower($validated->get('email')));
        $companyData = $validated->get('company') ?: [];
        if (isset($companyData['email'])) {
            $companyData['email'] = Str::lower(trim($companyData['email']));
        }

        $companyData['phones'] = CompanyRequestHelper::handlePhones($companyData['phones'] ?? []);
        $validated->put('company', $companyData);

        return $validated;
    }
}
