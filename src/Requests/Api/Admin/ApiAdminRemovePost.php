<?php

namespace DaydreamLab\User\Requests\Api\Admin;

use DaydreamLab\JJAJ\Requests\AdminRequest;

class ApiAdminRemovePost extends AdminRequest
{
    protected $modelName = 'Api';

    protected $apiMethod = 'deleteApi';
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
            'ids'       => 'required|array',
            'ids.*'     => 'required|integer'
        ];
        return array_merge($rules, parent::rules());
    }
}
