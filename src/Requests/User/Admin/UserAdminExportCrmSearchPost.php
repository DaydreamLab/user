<?php

namespace DaydreamLab\User\Requests\User\Admin;

class UserAdminExportCrmSearchPost extends UserAdminCrmSearchPost
{
    protected $modelName = 'User';

    protected $apiMethod = 'exportCrmSearch';

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
        ];

        return array_merge(parent::rules(), $rules);
    }
}