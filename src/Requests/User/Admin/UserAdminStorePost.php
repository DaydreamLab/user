<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\Dsth\Helpers\EnumHelper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;
use DaydreamLab\User\Helpers\CompanyRequestHelper;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use DaydreamLab\User\Helpers\EnumHelper as UserEnumHelper;

class UserAdminStorePost extends AdminRequest
{
    protected $modelName = 'User';

    protected $apiMethod = 'storeUser';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }


    public function handleCompany($company)
    {
        $company = $company ? collect($company) : collect();
        $keys = [
            'company_id',
            'quit',
            'name',
            'email',
            'vat',
//            'phoneCode',
//            'phone',
//            'extNumber',
            'country',
            'state',
            'city',
            'district',
            'address',
            'zipcode',
            'department',
            'jobTitle'
        ];
        foreach ($keys as $key) {
            if ($key == 'company_id') {
                $company->put('company_id', $company->get($key));
                $company->forget('id');
            } elseif ($key == 'email') {
                $company->put('email', Str::lower($company->get('email')));
            } elseif ($key == 'state') {
                $company->put('state_', $company->get($key));
                $company->forget('state');
            } else {
                $company->put($key, $company->get($key));
            }
        }

        $company->put('phones', CompanyRequestHelper::handlePhones($company['phones']));

        return $company->all();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'id'                    => 'nullable|integer',
            'email'                 => 'required|email',
            'name'                  => 'required|string',
            'firstName'             => 'nullable|string',
            'lastName'              => 'nullable|string',
            'nickname'              => 'nullable|string',
            'gender'                => 'nullable|string',
            'image'                 => 'nullable|string',
            'birthday'              => 'nullable|date',
            'phoneCode'             => 'nullable|string',
            'phone'                 => 'nullable|string',
            'mobilePhone'           => ['nullable', Rule::unique('users')->ignore($this->get('id'))],
            'backupMobilePhone'     => 'nullable|numeric',
            'country'               => 'nullable|string',
            'state'                 => 'nullable|string',
            'city'                  => 'nullable|string',
            'district'              => 'nullable|string',
            'address'               => 'nullable|string',
            'zipcode'               => 'nullable|string',
            'blockReason'           => 'nullable|string',
            'upgradeReason'         => 'nullable|string',
            'timezone'              => 'nullable|string',
            'locale'                => 'nullable|string',
            'brandIds'              => 'nullable|array',
            'brandIds.*'            => 'nullable|integer',
            'groupIds'              => 'required|array',
            'groupIds.*'            => 'nullable|integer',
            'block'                 => [
                'nullable',
                Rule::in([0,1])
            ],
            'resetPassword'         => [
                'nullable',
                Rule::in([0,1])
            ],
            'activation'            => [
                'nullable',
                Rule::in([0,1])
            ],
            'password'              => 'nullable|string|min:4|max:12',
            'passwordConfirm'       => 'required_with:password|same:password',
            'company'               => 'nullable|array',
            'company.id'            => 'nullable|integer',
            'company.vat'          => ['nullable', new TaiwanUnifiedBusinessNumber()],
            'company.quit'          => ['nullable', Rule::in([0,1])],
            'company.phones.*.phoneCode' => 'required|numeric',
            'company.phones.*.phone' => 'required|numeric',
            'company.phones.*.ext'  => 'nullable|numeric',
//            'company.phoneCode'     => 'nullable|string',
//            'company.phone'         => 'nullable|string',
//            'company.extNumber'     => 'nullable|string',
            'company.country'       => 'nullable|string',
            'company.state'         => 'nullable|string',
            'company.city'          => 'nullable|string',
            'company.district'      => 'nullable|string',
            'company.address'       => 'nullable|string',
            'company.zipcode'       => 'nullable|string',
            'company.department'    => 'nullable|string',
            'company.jobTitle'      => 'nullable|string',
            'company.jobCategory'    => 'nullable|string',
            'company.jobType'      => 'nullable|string',
//            'company.industry'      => 'nullable|string',
            'company.scale'         => 'nullable|string',
            'company.purchaseRole'  => 'nullable|string',
            'company.interestedIssue'   => 'nullable|array',
            'company.interestedIssue.*' => 'nullable|string',
            'company.issueOther'    => 'nullable|string',
            'company.validateStatus'    => [
                'nullable',
                Rule::in(
                    UserEnumHelper::DEALER_VALIDATE_WAIT,
                    UserEnumHelper::DEALER_VALIDATE_PASS,
                    UserEnumHelper::DEALER_VALIDATE_EXPIRED
                )
            ],
            'subscribeNewsletter'       => ['nullable', Rule::in(EnumHelper::BOOLEAN)],
            'cancelReason'       => ['nullable', Rule::in([
                UserEnumHelper::SUBSCRIBE_SELF_CANCEL,
                UserEnumHelper::SUBSCRIBE_EMAIL_CANCEL,
                UserEnumHelper::SUBSCRIBE_PHONE_CANCEL,
                UserEnumHelper::SUBSCRIBE_SALES_CANCEL,
            ])]
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        if (!$validated->get('id')) {
            $validated->put('password', bcrypt($validated->get('password')));
        } else {
            if ($validated->get('password')) {
                $validated->put('password', bcrypt($validated->get('password')));
            } else {
                $validated->forget('password');
            }
        }

        $pageGroupId = $this->get('pageGroupId');
        if ($pageGroupId === 16) {
            $validated->put('editAdmin', 1);
        }
        $validated->put('groupIds', $validated->get('groupIds') ?: []);
        $validated->put('email', Str::lower($validated->get('email')));
        $validated->put('company', $this->handleCompany($validated->get('company')));

        return $validated;
    }
}
