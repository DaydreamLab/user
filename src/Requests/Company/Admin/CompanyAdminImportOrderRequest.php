<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class CompanyAdminImportOrderRequest extends AdminRequest
{
    protected $modelName = 'Company';

    protected $apiMethod = 'importOrder';

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
        $rules = [
            'file'  => 'required',
        ];
        return array_merge($rules, parent::rules());
    }
}
