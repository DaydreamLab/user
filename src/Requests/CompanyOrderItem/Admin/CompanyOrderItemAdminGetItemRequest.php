<?php

namespace DaydreamLab\User\Requests\CompanyOrderItem\Admin;

use DaydreamLab\User\Requests\ComponentBase\UserGetItemRequest;

class CompanyOrderItemAdminGetItemRequest extends UserGetItemRequest
{
    protected $modelName = 'CompanyOrderItem';

    protected $apiMethod = 'getCompanyOrderItem';
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