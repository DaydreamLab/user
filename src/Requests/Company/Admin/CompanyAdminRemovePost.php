<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class CompanyAdminRemovePost extends AdminRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'deleteCompany';
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
            'ids'       => 'required|array',
            'ids.*'     => 'required|integer'
        ];
    }
}
