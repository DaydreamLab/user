<?php

namespace DaydreamLab\User\Requests\User\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;
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
        $keys = ['id', 'quit', 'name', 'vat', 'phoneCode', 'phone', 'extNumber', 'country', 'state', 'city', 'district', 'address', 'zipcode', 'department', 'jobTitle'];
        foreach ($keys as $key) {
            if ($key == 'id') {
                $company->put('company_id', $company->get($key));
                $company->forget('id');
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
            'password'              => 'nullable|string|min:8|max:16',
            'passwordConfirm'       => 'required_with:password|same:password',
            'company'               => 'nullable|array',
            'company.id'            => 'nullable|integer',
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

            'newsletterCategoriesAlias'     => 'nullable|array',
            'newsletterCategoriesAlias.*'   => 'nullable|string'
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

        $validated->put('groupIds', $validated->get('groupIds') ?: []);
        $validated->put('company', $this->handleCompany($validated->get('company')));

        return $validated;
    }
}
