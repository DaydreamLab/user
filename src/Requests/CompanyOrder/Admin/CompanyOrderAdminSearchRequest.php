<?php

namespace DaydreamLab\User\Requests\CompanyOrder\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserSearchRequest;

class CompanyOrderAdminSearchRequest extends UserSearchRequest
{
    protected $modelName = 'CompanyOrder';

    protected $apiMethod = 'searchCompanyOrder';

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
            'brandId' => 'nullable|integer',
            'startDate' => 'nullable|date_format:Y-m',
            'endDate' => 'nullable|date_format:Y-m',
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();
        $validated->put('companyId', $this->route('companyId'));

        return $validated;
    }
}
