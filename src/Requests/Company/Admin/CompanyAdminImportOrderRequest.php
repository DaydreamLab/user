<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\Cms\Helpers\RequestHelper;
use DaydreamLab\Cms\Requests\ComponentBase\CmsStoreRequest;
use Illuminate\Validation\Rule;

class CompanyAdminImportOrderRequest extends CmsStoreRequest
{
    protected $apiMethod = 'importOrder';

    protected $modelName = 'Company';
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
            'file'  => 'required|file'
        ];

        return array_merge(parent::rules(), $rules);
    }


    public function validated()
    {
        $validated = parent::validated();

        return $validated;
    }
}
