<?php

namespace DaydreamLab\User\Requests\CompanyOrder\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserGetItemRequest;

class CompanyOrderAdminGetItemRequest extends UserGetItemRequest
{
    protected $modelName = 'CompanyOrder';

    protected $apiMethod = 'getCompanyOrder';
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
            //
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
