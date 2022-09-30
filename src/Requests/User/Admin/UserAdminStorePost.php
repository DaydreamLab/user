<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\Dsth\Helpers\EnumHelper;
use DaydreamLab\JJAJ\Requests\AdminRequest;
use DaydreamLab\JJAJ\Rules\TaiwanUnifiedBusinessNumber;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'phoneCode',
            'phone',
            'extNumber',
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
            'mobilePhone'           => 'nullable|string',
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
            'company.phoneCode'     => 'nullable|string',
            'company.phone'         => 'nullable|string',
            'company.extNumber'     => 'nullable|string',
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
//            'company.scale'         => 'nullable|string',
            'company.purchaseRole'  => 'nullable|string',
            'company.interestedIssue'   => 'nullable|array',
            'company.interestedIssue.*' => 'nullable|string',
            'company.issueOther'    => 'nullable|string',
            'subscribeNewsletter'       => ['nullable', Rule::in(EnumHelper::BOOLEAN)]
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
