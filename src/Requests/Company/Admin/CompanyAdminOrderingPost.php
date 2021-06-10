<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class CompanyAdminOrderingPost extends AdminRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'orderingCompany';
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
            'id'          => 'required|integer',
            'ordering'    => 'nullable|integer',
        ];

        return array_merge(parent::rules(), $rules);
    }
}