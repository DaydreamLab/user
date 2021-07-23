<?php

namespace DaydreamLab\User\Requests\Company\Admin;

use DaydreamLab\Cms\Requests\CmsRestoreRemovePost;

class CompanyAdminRestorePost extends CmsRestoreRemovePost
{
    protected $apiMethod = 'restoreCompany';

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
            //
        ];
        return array_merge(parent::rules(), $rules);
    }
}
